<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Interfaces\BlogGalleryInterface;
use App\Interfaces\BlogInterface;

class BlogGalleryController extends Controller
{
    private $param;
    private $blog_gallery, $blog;

    public function __construct(BlogGalleryInterface $blog_gallery, BlogInterface $blog)
    {
        $this->blog_gallery = $blog_gallery;
        $this->blog = $blog;
    }

    public function list(Request $request, $id) : View
    {
        $this->param['search'] = $request['search'] ?? '';
        $this->param['per_page'] = $request['show'] ?? 10;
        $this->param['data_blog_gallery'] = $this->blog_gallery->list($request->all(), $id);

        $this->param['detail_blog'] = $this->blog->detail($id);

        return view('pages.blog-gallery.list', $this->param);
    }

    public function store(Request $request, $id) : RedirectResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'caption' => 'required',
        ], [
            'required' => ':attribute harus diisi',
            'image' => ':attribute harus berupa gambar',
            'mimes' => ':attribute harus berupa gambar dengan ekstensi jpeg, png, dan jpg',
            'max' => ':attribute maksimal :max KB',
        ], [
            'image' => 'Gambar',
            'caption' => 'Keterangan',
        ]);

        DB::beginTransaction();
        try {
            $date = date('Y-m-d H-i-s');
            $random = Str::random(5);
            $new_file_name = null;

            if ($request->file('image')) {
                $new_file_name = 'uploads/blog-gallery/gallery-'.Str::slug($request->name).$random.'-'.$date.'.'.$request->image->extension();
                $request->file('image')->move('uploads/blog-gallery/', $new_file_name);
            }

            $this->blog_gallery->store([
                'blog_id' => $id,
                'document' => $new_file_name,
                'caption' => $request->caption,
            ]);

            DB::commit();
            return redirect()->route('blog.blog-gallery.list', $id)->with('success', 'Data berhasil disimpan');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function edit($id, $gallery_id) : View
    {
        $this->param['detail_blog'] = $this->blog->detail($id);
        $this->param['detail_blog_gallery'] = $this->blog_gallery->detail($gallery_id);

        return view('pages.blog-gallery.edit', $this->param);
    }

    public function update(Request $request, $id, $gallery_id) : RedirectResponse
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'caption' => 'required',
        ], [
            'required' => ':attribute harus diisi',
            'image' => ':attribute harus berupa gambar',
            'mimes' => ':attribute harus berupa gambar dengan ekstensi jpeg, png, dan jpg',
            'max' => ':attribute maksimal :max KB',
        ], [
            'image' => 'Gambar',
            'caption' => 'Keterangan',
        ]);

        DB::beginTransaction();
        try {
            $date = date('Y-m-d H-i-s');
            $random = Str::random(5);
            $new_file_name = null;

            if ($request->file('image')) {
                $new_file_name = 'uploads/blog-gallery/gallery-'.Str::slug($request->name).$random.'-'.$date.'.'.$request->image->extension();
                $request->file('image')->move('uploads/blog-gallery/', $new_file_name);
            } else {
                $new_file_name = $this->blog_gallery->detail($gallery_id)->document;
            }

            $this->blog_gallery->update($gallery_id, [
                'document_rename' => $new_file_name,
                'caption' => $request->caption,
            ]);

            DB::commit();
            return redirect()->route('blog.blog-gallery.list', $id)->with('success', 'Data berhasil diubah');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function destroy($id, $gallery_id) : RedirectResponse
    {
        DB::beginTransaction();
        try {
            $this->blog_gallery->destroy($gallery_id);

            DB::commit();
            return redirect()->route('blog.blog-gallery.list', $id)->with('success', 'Data berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan pada database, '. $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withError($e->getMessage());
        }
    }
}
