<?php

namespace App\Interfaces;

interface UserInterface
{
    public function list($request);
    public function detail($id);
    public function store($data);
    public function update($id, $data);
    public function destroy($id);
    public function activated_deactivated($id, $data);
    
    public function all();
    public function detail_by_email($email, array $relations = []);
}
