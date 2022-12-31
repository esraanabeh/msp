<?php

namespace Modules\Templates\Repositories\Interfaces;

interface ITemplateRepository{
    // public function getServices();
    public function createTemplate($data);
    public function listallSections($templateId);
    public function showTemplate($id);







}









?>
