<?php

namespace App\Interfaces;

interface ResponseInterface
{
    public function list($request, $journal_id);
    public function list_by_user($request, $user_id, array $relations = []);
    public function detail($id, array $relations = []);
    public function detail_by_code($code);
    public function store($data);

    public function all_by_user($user_id);
}