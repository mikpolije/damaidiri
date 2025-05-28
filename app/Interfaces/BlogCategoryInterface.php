<?php

namespace App\Interfaces;

interface BlogCategoryInterface
{
    public function list($request);
    public function detail($id);
    public function store($data);
    public function update($id, $data);
    public function destroy($id);

    public function all();
}