<?php

namespace Modules\Members\Repositories\Interfaces;

interface IMemberRepository{
    public function getMembers();
    public function createMember($datta);
    public function updateMember($id ,  $request);
    public function deleteMember($id);
    public function updateMemberStatus( $request);



}









?>
