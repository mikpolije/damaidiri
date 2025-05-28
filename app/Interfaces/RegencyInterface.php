<?php

namespace App\Interfaces;

interface RegencyInterface
{
    public function list($request, array $relations = []);
    public function detail($id);
    public function store($data);
    public function update($id, $data);
    public function destroy($id);

    public function all();
}