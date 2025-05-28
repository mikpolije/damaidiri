<?php

namespace App\Interfaces;

interface JournalInterface
{
    public function list($request);
    public function detail($id);
    public function store($data);
    public function update($id, $data);
    public function destroy($id);

    public function all();
}