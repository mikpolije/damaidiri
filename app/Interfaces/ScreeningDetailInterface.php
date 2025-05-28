<?php

namespace App\Interfaces;

interface ScreeningDetailInterface
{
    public function store($data);
    public function get_by_screening($screening_id);
}