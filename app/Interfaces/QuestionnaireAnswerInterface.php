<?php

namespace App\Interfaces;

interface QuestionnaireAnswerInterface
{
    public function list($request, $questionnaire_id);
    public function detail($id);
    public function store($data);
    public function update($id, $data);
    public function destroy($id);

    public function all_by_questionnaire($questionnaire_id);
}
