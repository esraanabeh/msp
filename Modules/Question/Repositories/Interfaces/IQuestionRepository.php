<?php

namespace Modules\Question\Repositories\Interfaces;

interface IQuestionRepository{

    public function findOrCreateQuestion($data);
    public function updateOrCreateClientQuestion($clientId,$question,$data);



}
