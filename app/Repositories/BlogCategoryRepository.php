<?php

namespace App\Repositories;

use App\Interfaces\BlogCategoryInterface;
use App\Models\BlogCategory;

use Illuminate\Support\Facades\DB;

class BlogCategoryRepository implements BlogCategoryInterface
{
    private $blog_category;

    public function __construct(BlogCategory $blog_category)
    {
        $this->blog_category = $blog_category;
    }

    public function list($request)
    {
        $per_page = $request['show'] ?? 10;
        $search = $request['search'] ?? '';

        $qr = BlogCategory::query();
        $qr->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
               $subQuery->where('name', 'like', '%'.$search.'%')
                        ->orWhere('color_code', 'like', '%'.$search.'%');
            });
        });
        $data = $qr->orderBy('created_at', 'desc')->paginate($per_page); 
        
        return $data;
    }

    public function detail($id)
    {
        return $this->blog_category->find($id);
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $this->blog_category->create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'color_code' => $data['color_code'],
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
            $this->blog_category->find($id)->update([
                'name' => $data['name'],
                'color_code' => $data['color_code'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }

    public function destroy($id)
    {
        return $this->blog_category->find($id)->delete();
    }

    public function all()
    {
        return $this->blog_category->all();
    }
}
