<?php

namespace App\Interfaces;

interface PatientInterface
{
    public function store($data);
    public function update($user_id, $data);
    public function delete_by_user($user_id);
}
