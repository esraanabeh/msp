<?php

namespace Modules\Team\Repositories\Interfaces;

interface ITeamRepository{
    public function getTeams();
    public function createTeam($datta);
    public function updateTeam($id ,  $name);
    public function deleteTeam($id);



}









?>
