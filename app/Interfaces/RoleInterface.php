<?php

namespace App\Interfaces;

interface RoleInterface
{
    public function list($request);
    public function detail($id);
    public function update($id, $data);

    public function all();
}
