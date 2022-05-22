<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Book;
use App\Models\Member;
use App\Models\Penalty;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    public function index($opt)
    {
        switch ($opt) {
            case 'login':
                return view('member.index');
            case 'register':
                return view('member.register');
            case 'loss':
                return view('member.loss');
            default:
                return view('layout.404');
        }
    }

    public function loginByCard(Request $request)
    {
        $credentials = $request->only('id', 'password');
        if (Auth::guard('memberCard')->attempt($credentials)) {
            return $this->success('登录成功', Auth::guard('memberCard')->user()->toArray());
        }
        return $this->failure('无效的会员卡');
    }

    public function loginByPassword(Request $request)
    {
        $credentials = $request->only('telephone', 'password');
        if (Auth::guard('memberPassword')->attempt($credentials)) {
            return $this->success('登录成功', Auth::guard('memberPassword')->user()->toArray());
        }
        return $this->failure('手机号和密码不匹配');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recharge(Request $request)
    {
        /** @var Member $total */
        $total = Auth::guard('memberCard')->user();
        $total->balance += $request->input('chargeNum', 0);
        $total->save();
        return $this->success('充值成功');
    }

    public function logout(Request $request)
    {
        Auth::guard('memberCard')->logout();
        Auth::guard('memberPassword')->logout();
        return $this->success('登出成功');
    }

    public function borrowList(Request $request)
    {
        /** @var Member $total */
        $total = Auth::guard('memberCard')->user();
        $page = $request->input('page', 1);
        $pageSize = $request->input('limit', 10);
        $query = $request->input('query', []);
        $qb = Bill::where('mid', $total->id)
                  ->orderBy('created_at', 'desc')
                  ->orderBy('return')
                  ->with(['member', 'book']);
        if (isset($query['name'])) {
            $qb->whereHas('book', function (Builder $builder) use ($query) {
                $builder->where('name', 'like', "%{$query['name']}%");
            });
        }
        if (isset($query['author'])) {
            $qb->whereHas('book', function (Builder $builder) use ($query) {
                $builder->where('author', 'like', "%{$query['author']}%");
            });
        }
        if (isset($query['ISBN'])) {
            $qb->whereHas('book', function (Builder $builder) use ($query) {
                $builder->where('ISBN', 'like', "%{$query['ISBN']}%");
            });
        }
        if (isset($query['return'])) {
            $qb->where('return', $query['return']);
        }
        $count = $qb->count();
        $list = $qb->forPage($page, $pageSize)->get();
        $list = $list->each(function (Bill $bill) {
            $duration = $bill->duration;
//            如果有延期则加10天
            if ($bill->delay) {
                $duration += 10;
            }
//            取整天
            $return = Carbon::parse($bill->started_at)->addDays($duration)->floorDay();
            $bill->setAttribute('returnDay', $return->format('Y-m-d'));
            $bill->setAttribute('returnTimestamp', $return->getTimestampMs());
        });
        $data = [
            'data' => $list->toArray(),
            'meta' => compact('count', 'query')
        ];
        return $this->success('', $data);
    }

    public function borrow(Request $request)
    {
        /** @var Member $total */
        $total = Auth::guard('memberCard')->user();
        if ($book = Book::where('ISBN', $request->input('ISBN'))->first()) {
//            检查该书库存
            if ($book->num < 1) {
                return $this->failure('该书库存不足');
            }
//            检查余额
            if ($total->balance < $book->price) {
                return $this->failure('余额不足');
            }
//            检查是否借书未还
            $isBorrowing = (bool)$total->bills()->where('bid', $book->id)->where('return', false)->first();
            if ($isBorrowing) {
                return $this->failure('该用户已借出此书');
            }
            $book->num--;
            $book->lend++;
            DB::beginTransaction();
            $book->bills()->create([
                'mid'        => $total->id,
                'bill_no'    => (string)Str::uuid(),
                'started_at' => Carbon::parse(),
            ]);
            $book->save();
            DB::commit();
            return $this->success('保存成功');
        }
        return $this->failure('未找到该书');
    }

    public function delay(Request $request)
    {
        $billNo = $request->input('bill_no');
        $bill = Bill::where('bill_no', $billNo)->first();
        if ($bill->delay) {
            return $this->failure('该订单已续借过，不能再续借');
        }
        if ($bill->return) {
            return $this->failure('该订单已归还，不能再续借');
        }
        $bill->delay = true;
        $bill->save();
        return $this->success('续借成功。');
    }

    public function showCost(Request $request)
    {
        $no = $request->input('bill_no');
        $bill = Bill::where('bill_no', $no)->where('return', false)->first();
        if (!$bill) {
            return $this->failure('未找到该记录');
        }
        $costList = $this->checkCost($bill);
        return $this->success('', $costList);
    }

    public function return(Request $request)
    {
        $no = $request->input('bill_no');
        $bill = Bill::where('bill_no', $no)->first();
        $book = $bill->book;
        /** @var Member $total */
        $total = Auth::guard('memberCard')->user();
        if ($bill->return) {
            return $this->failure('该书已归还，无需再次归还');
        }
        $costList = $this->checkCost($bill);
        if ($total->balance < $costList['count'][1]) {
            return $this->failure('余额不足');
        }
        $total->balance -= $costList['count'][1];
        $bill->return = true;
        $bill->money = $costList['count'][1];
        $bill->ended_at = Carbon::parse();
        $book->num += 1;
        $book->lend -= 1;
        DB::beginTransaction();
        $bill->save();
        $total->save();
        $book->save();
        if (isset($costList['penalty'])) {
            $bill->penalty()->create(['money' => $costList['penalty'][1], 'type' => Penalty::TYPE_BALANCE]);
        }
        DB::commit();
        return $this->success('还书成功');
    }

    private function checkCost(Bill $bill, ?Carbon $diffDay = null)
    {
        $costList['normal'] = ['借书费用', $bill->book->price];
        $date = Carbon::parse($bill->started_at);
        if ($bill->delay) {
            $costList['delay'] = ['续借费用', 5];
            $date->addDays(10);
        }
        $date->addDays(30);
        $diffDays = $date->diffInDays($diffDay, false);
        if ($diffDays > 0) {
            $costList['penalty'] = ['超期罚款', $diffDays * 0.5];
        }
        $cost = round(array_sum(array_column($costList, 1)), 2);
        $costList['count'] = ['总计', $cost];
        return $costList;
    }

    public function edit(Request $request)
    {
        /** @var Member $total */
        $total = Auth::guard('memberCard')->user();
        $data = $request->except('_token');
        $total->update($data);
        return $this->success('更新成功');
    }


    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'name'       => 'bail|required',
                'telephone'  => 'bail|required|numeric|unique:members,telephone',
                'age'        => 'bail|required|digits_between:1,2',
                'gender'     => 'bail|required|bool',
                'password'   => 'bail|required|alpha_num|size:6,12',
                'profession' => 'bail|required|alpha_num',
                'repass'     => 'bail|required|same:password',
            ], ['telephone.unique' => '该手机号已注册']);
        } catch (\Throwable $throwable) {
            return $this->failure('数据格式错误！');
        }

        $new = new Member;
        $new->name = $data['name'];
        $new->gender = $data['gender'];
        $new->age = $data['age'];
        $new->profession = $data['profession'];
        $new->telephone = $data['telephone'];
        $new->password = Hash::make($data['password']);
        $new->deposit = 100;
        $new->save();
        Auth::guard('memberCard')->login($new);
        return $this->success('注册成功');
    }

    public function loss(Request $request)
    {
        $new = $request->input('new');
        /** @var Member $total */
        $total = Auth::guard('memberPassword')->user();
        if (!$total) {
            return $this->failure('未登录！');
        }
        $total->password = Hash::make($new);
        $total->save();
        Auth::guard('memberPassword')->logout();
        Auth::guard('memberCard')->login($total);
        return $this->success('修改成功');
    }
}
