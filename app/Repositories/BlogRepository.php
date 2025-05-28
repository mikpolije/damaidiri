<?php

namespace App\Repositories;

use App\Interfaces\BlogInterface;
use App\Models\Blog;

use Illuminate\Support\Facades\DB;

class BlogRepository implements BlogInterface
{
    private $blog;

    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }

    public function list($request, array $relations = [])
    {
        $per_page = $request['show'] ?? 10;
        $search = $request['search'] ?? '';
        $category_id = $request['category_id'] ?? null;

        $qr = Blog::query();
        if (!empty($relations)) {
            $qr->with($relations);
        }
        $qr->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
               $subQuery->where('title', 'like', '%'.$search.'%')
                        ->orWhereRelation('blog_category', 'name', 'like', '%'.$search.'%')
                        ->orWhereRelation('author', 'name', 'like', '%'.$search.'%');
            });
        })->when($category_id, function ($query) use ($category_id) {
            return $query->where('blog_category_id', (int) $category_id);
        });
        $data = $qr->orderBy('created_at', 'desc')->paginate($per_page); 
        
        return $data;
    }

    public function detail($id, array $relations = [])
    {
        if (!empty($relations)) {
            return $this->blog->with($relations)->find($id);
        }
        return $this->blog->find($id);
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $announcement = $this->blog->create([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'thumbnail' => $data['thumbnail_rename'],
                'description' => $data['description'],
                'blog_category_id' => $data['blog_category_id'],
                'author_id' => $data['author_id'],
            ]);

            DB::commit();
            return $announcement;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function update($id, $data)
    {
        DB::beginTransaction();
        try {
            $this->blog->find($id)->update([
                'title' => $data['title'],
                'thumbnail' => $data['thumbnail_rename'],
                'description' => $data['description'],
                'blog_category_id' => $data['blog_category_id'],
                'author_id' => $data['author_id'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }

    public function destroy($id)
    {
        return $this->blog->find($id)->delete();
    }

    public function delete_by_blog_category($blog_category_id)
    {
        return $this->blog->where('blog_category_id', $blog_category_id)->delete();
    }

    public function delete_by_author($author_id)
    {
        return $this->blog->where('author_id', $author_id)->delete();
    }

    public function all()
    {
        return $this->blog->all();
    }
}
