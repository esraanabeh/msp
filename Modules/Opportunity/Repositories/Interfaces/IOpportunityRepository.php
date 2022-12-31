<?php

namespace Modules\Opportunity\Repositories\Interfaces;

interface IOpportunityRepository{

    public function listOPPortunities();
    public function createOPPortunities($data);
    public function previewOPPortunities($id);

}

?>
