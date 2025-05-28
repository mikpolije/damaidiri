<?php

namespace App\Interfaces;

interface QuestionnaireDetailCategoryInterface
{
    public function list($request, $questionnaire_id);
    public function detail($id);
    public function store($data);
    public function update($id, $data);
    public function destroy($id);

    public function find_by_range($questionnaire_category_id, $score);
    public function count_by_questionnaire($questionnaire_id);
}
