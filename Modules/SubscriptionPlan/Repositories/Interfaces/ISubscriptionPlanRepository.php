<?php

namespace Modules\SubscriptionPlan\Repositories\Interfaces;

interface ISubscriptionPlanRepository{

    public function listPlans();
    public function subscripeTrial();
    public function getMyPlan();

}









?>
