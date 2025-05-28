<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Interfaces\BlogCategoryInterface;
use App\Interfaces\BlogGalleryInterface;
use App\Interfaces\BlogInterface;

class BlogController extends Controller
{
    private $param;
    private $blog, $blog_category, $blog_gallery;

    public function __construct(BlogInterface $blog, BlogCategoryInterface $blog_category, BlogGalleryInterface $blog_gallery)
    {
        $this->blog = $blog;
        $this->blog_category = $blog_category;
        $this->blog_gallery = $blog_gallery;
    }

    public function list(Request $request) : View
    {
        $this->param['search'] = $request['search'] ?? '';
        $this->param['per_page'] = $request['show'] ?? 10;
        $this->param['data_blog'] = $this->blog->list($request->all());

        return view('pages.blog.list', $this->param);
    }

    public function create() : View
    {
        $this->param['data_blog_category'] = $this->blog_category->all();

        return view('pages.blog.add', $this->param);
    }

    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
            'title' => 'required|unique:blogs,title',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'blog_category_id' => 'required',
            'author' => 'required',
            'description' => 'required',
            'document.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'caption.*' => 'nullable|max:255'
        ], [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute sudah ada',
            'image' => ':attribute harus berupa gambar',
            'mimes' => ':attribute harus berupa gambar dengan ekstensi jpeg, png, dan jpg',
            'max' => ':attribute maksimal :max KB',
        ], [
            'title' => 'Judul',
            'thumbnail' => 'Gambar (Thumbnail)',
            'blog_category_id' => 'Kategori',
            'author' => 'Penulis',
            'description' => 'Deskripsi',
        ]);

        DB::beginTransaction();
        try {
            $date = date('Y-m-d H-i-s');
            $random = Str::random(5);
            $new_file_name = null;

            if ($request->file('thumbnail')) {
                $new_file_name = 'uploads/blog/blog-'.Str::slug($request->name).$random.'-'.$date.'.'.$request->thumbnail->extension();
                $request->file('thumbnail')->move('uploads/blog/', $new_file_name);
            }

            $request->merge([
                'slug' => Str::slug($request->title),
                'thumbnail_rename' => $new_file_name,
                'author_id' => auth()->user()->id,
            ]);
            $announcement = $this->blog->store($request->all());

            if ($request->hasFile('document')) {
                foreach ($request->file('document') as $item => $document) {
                    $random_gallery = Str::random(5);
                    $gallery_path = 'uploads/blog-gallery/gallery-'.Str::slug($request->title).$random_gallery.'-'.$date.'.'.$document->extension();
                    $document->move('uploads/blog-gallery/', $gallery_path);

                    $this->blog_gallery->store([
                        'blog_id' => $announcement->id,
                        'document' => $gallery_path,
                        'caption' => $request->caption[$item] ?? '-',
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('blog.list')->with('success', 'Data berhasil ditambahkan');
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
        $this->param['detail_blog'] = $this->blog->detail($id);
        $this->param['data_blog_gallery'] = $this->blog_gallery->all_by_blog($id);

        return view('pages.blog.detail', $this->param);
    }

    public function edit($id) : View
    {
        $this->param['detail_blog'] = $this->blog->detail($id);
        $this->param['data_blog_category'] = $this->blog_category->all();

        return view('pages.blog.edit', $this->param);
    }

    public function update(Request $request, $id) : RedirectResponse
    {
        $request->validate([
            'title' => 'required|unique:blogs,title,'.$id,
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'blog_category_id' => 'required',
            'author' => 'required',
            'description' => 'required',
        ], [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute sudah ada',
        ], [
            'title' => 'Judul',
            'thumbnail' => 'Gambar (Thumbnail)',
            'blog_category_id' => 'Kategori',
            'author' => 'Penulis',
            'description' => 'Deskripsi',
        ]);

        DB::beginTransaction();
        try {
            $date = date('Y-m-d H-i-s');
            $random = Str::random(5);
            $new_file_name = null;

            if ($request->file('thumbnail')) {
                $new_file_name = 'uploads/blog/blog-'.Str::slug($request->name).$random.'-'.$date.'.'.$request->thumbnail->extension();
                $request->file('thumbnail')->move('uploads/blog/', $new_file_name);
            } else {
                $new_file_name = $this->blog->detail($id)->thumbnail;
            }

            $request->merge([
                'thumbnail_rename' => $new_file_name,
                'author_id' => auth()->user()->id,
            ]);
            $announcement = $this->blog->update($id, $request->all());

            DB::commit();
            return redirect()->route('blog.list')->with('success', 'Data berhasil diperbarui');
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
            $this->blog->destroy($id);

            DB::commit();
            return redirect()->route('blog.list')->with('success', 'Data berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }
}
