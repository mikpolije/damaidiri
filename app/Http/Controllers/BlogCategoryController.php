<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Interfaces\BlogCategoryInterface;
use App\Interfaces\BlogInterface;

class BlogCategoryController extends Controller
{
    private $param;
    private $blog_category, $blog;

    public function __construct(BlogCategoryInterface $blog_category, BlogInterface $blog)
    {
        $this->blog_category = $blog_category;
        $this->blog = $blog;
    }

    public function list(Request $request) : View
    {
        $this->param['search'] = $request['search'] ?? '';
        $this->param['per_page'] = $request['show'] ?? 10;
        $this->param['data_blog_category'] = $this->blog_category->list($request->all());

        return view('pages.blog-category.list', $this->param);
    }

    public function create() : View
    {
        return view('pages.blog-category.add');
    }

    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
           'name' => 'required|unique:blog_categories,name',
           'color_code' => 'required|unique:blog_categories,color_code',
        ], [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute sudah ada',
        ], [
            'name' => 'Nama Kategori',
            'color_code' => 'Kode Warna',
        ]);

        DB::beginTransaction();
        try {
            $request->merge(['slug' => Str::slug($request->name)]);
            $this->blog_category->store($request->all());

            DB::commit();
            return redirect()->route('blog-category.list')->with('success', 'Data berhasil ditambahkan');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function detail($id) : View
    {
        $this->param['detail_blog_category'] = $this->blog_category->detail($id);

        return view('pages.blog-category.detail', $this->param);
    }

    public function edit($id) : View
    {
        $this->param['detail_blog_category'] = $this->blog_category->detail($id);

        return view('pages.blog-category.edit', $this->param);
    }

    public function update(Request $request, $id) : RedirectResponse
    {
        $request->validate([
            'name' => 'required|unique:blog_categories,name,'.$id,
            'color_code' => 'required|unique:blog_categories,color_code,'.$id,
        ], [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute sudah ada',
        ], [
            'name' => 'Nama Kategori',
            'color_code' => 'Kode Warna',
        ]);

        DB::beginTransaction();
        try {
            $this->blog_category->update($id, $request->all());

            DB::commit();
            return redirect()->route('blog-category.list')->with('success', 'Data berhasil ditambahkan');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function destroy($id) : RedirectResponse
    {
        DB::beginTransaction();
        try {
            $this->blog_category->destroy($id);
            $this->blog->delete_by_blog_category($id);

            DB::commit();
            return redirect()->route('blog-category.list')->with('success', 'Data berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }
}
