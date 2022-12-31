<?php

namespace Modules\Services\Repositories\Interfaces;

interface IServiceRepository{
    public function getServices();
    public function createService($request);
    public function createMasterAgreement($request);
    // public function updateService($id ,  $name);
    public function deleteServiceAgreement($id);




}









?>
