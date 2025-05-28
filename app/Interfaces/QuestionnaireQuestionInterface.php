<?php

namespace App\Interfaces;

interface QuestionnaireQuestionInterface
{
    public function list($request, $questionnaire_id, array $relations = []);
    public function detail($id);
    public function store($data);
    public function update($id, $data);
    public function destroy($id);
}
