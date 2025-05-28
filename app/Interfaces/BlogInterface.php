<?php

namespace App\Interfaces;

interface BlogInterface
{
    public function list($request, array $relations = []);
    public function detail($id);
    public function store($data);
    public function update($id, $data);
    public function destroy($id);

    public function delete_by_blog_category($blog_category_id);
    public function delete_by_author($author_id);

    public function all();
}