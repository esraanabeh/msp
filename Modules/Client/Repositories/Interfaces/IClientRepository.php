<?php

namespace Modules\Client\Repositories\Interfaces;

interface IClientRepository{

    public function createClient($data);
    public function listClients();
    public function handleClientQuestions($clientId,$data);
}

?>
