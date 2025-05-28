<?php

namespace App\Repositories;

use App\Interfaces\BlogGalleryInterface;
use App\Models\BlogGallery;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BlogGalleryRepository implements BlogGalleryInterface
{
    private $blog_gallery;

    public function __construct(BlogGallery $blog_gallery)
    {
        $this->blog_gallery = $blog_gallery;
    }

    public function list($request, $blog_id)
    {
        $per_page = $request['show'] ?? 10;
        $search = $request['search'] ?? '';

        $qr = BlogGallery::query();
        $qr->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
               $subQuery->where('caption', 'like', '%'.$search.'%');
            });
        });
        $data = $qr->where('blog_id', $blog_id)->orderBy('created_at', 'desc')->paginate($per_page); 
        
        return $data;
    }

    public function detail($id)
    {
        return $this->blog_gallery->find($id);
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $this->blog_gallery->create([
                'blog_id' => $data['blog_id'],
                'document' => $data['document'],
                'caption' => $data['caption'],
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function update($id, $data)
    {
        DB::beginTransaction();
        try {
            $this->blog_gallery->find($id)->update([
                'document' => $data['document_rename'],
                'caption' => $data['caption'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }

    public function destroy($id)
    {
        return $this->blog_gallery->find($id)->delete();
    }

    public function all_by_blog($blog_id)
    {
        return $this->blog_gallery->where('blog_id', $blog_id)->get();
    }
}
