<?php

namespace App\Interfaces;

interface PartnerInterface
{
    public function list($request, array $relations = []);
    public function detail($id, array $relations = []);
    public function store($data);
    public function update($id, $data);
    public function destroy($id);

    public function all();
}