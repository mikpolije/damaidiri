<?php

namespace App\Interfaces;

interface BlogGalleryInterface
{
    public function list($request, $blog_id);
    public function detail($id);
    public function store($data);
    public function update($id, $data);
    public function destroy($id);

    public function all_by_blog($blog_id);
}
