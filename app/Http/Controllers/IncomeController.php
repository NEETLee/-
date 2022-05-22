<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index()
    {
        return view('income.index');
    }

    public function list(Request $request)
    {
        $page = $request->input('page', 1);
        $pageSize = $request->input('limit', 10);
        $query = $request->input('query', []);
        $query = array_filter($query);
        $qb = Bill::with(['member', 'book', 'penalty'])->where('return', true)->orderBy('created_at', 'desc');
        if (isset($query['name'])) {
            $qb->whereHas('book', function (Builder $builder) use ($query) {
                $builder->where('name', 'like', "%{$query['name']}%");
            });
        }
        if (isset($query['ISBN'])) {
            $qb->whereHas('book', function (Builder $builder) use ($query) {
                $builder->where('ISBN', 'like', "%{$query['ISBN']}%");
            });
        }
        if (isset($query['telephone'])) {
            $qb->whereHas('member', function (Builder $builder) use ($query) {
                $builder->where('telephone', 'like', "%{$query['telephone']}%");
            });
        }
        if (isset($query['started_at'])) {
            $between = explode(' ~ ', $query['started_at']);
            $qb->whereBetween('started_at', $between);
        }
        if (isset($query['ended_at'])) {
            $between = explode(' ~ ', $query['ended_at']);
            $qb->whereBetween('ended_at', $between);
        }
        $count = $qb->count();
        $list = $qb->forPage($page, $pageSize)->get();
        $data = [
            'data' => $list->toArray(),
            'meta' => compact('count', 'query')
        ];
        return $this->success('', $data);
    }
}
