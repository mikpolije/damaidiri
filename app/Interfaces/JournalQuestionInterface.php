<?php

namespace App\Interfaces;

interface JournalQuestionInterface
{
    public function list($request, $journal_id);
    public function detail($id);
    public function store($data);
    public function update($id, $data);
    public function destroy($id);
}
