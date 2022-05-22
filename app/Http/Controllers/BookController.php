<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return view('book.index');
    }

    public function list(Request $request)
    {
        $page = $request->input('page', 1);
        $pageSize = $request->input('limit', 10);
        $query = $request->input('query', []);
        $query = array_filter($query);
        $qb = Book::orderBy('updated_at', 'desc');
        if (!empty($query)) {
            foreach ($query as $key => $value) {
                $qb->where($key, 'like', "%$value%");
            }
        }
        $count = $qb->count();
        $list = $qb->forPage($page, $pageSize)->get();
        $data = [
            'data' => $list->toArray(),
            'meta' => compact('count', 'query')
        ];
        return $this->success('', $data);
    }

    public function create(Request $request)
    {
        $data = $request->except('_token');
        if ($new = Book::create($data)) {
            return $this->success('新增成功', $new->toArray());
        } else {
            return $this->failure('新增失败');
        }
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        $data = $request->except('id', '_token');
        if ($book = Book::find($id)) {
            $book->update($data);
            return $this->success('修改成功');
        } else {
            return $this->failure('未找到该书');
        }
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        if ($book = Book::find($id)) {
            if ($book->lend > 0) {
                return $this->failure('该书已借出，无法删除');
            }
            if ($book->delete()) {
                return $this->success('删除成功');
            } else {
                return $this->failure('删除失败');
            }
        } else {
            return $this->failure('未找到该书');
        }
    }
}
