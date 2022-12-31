<?php
namespace Modules\Members\Observers;

use Modules\Authentication\Repositories\Repos\PasswordRepository;
use Modules\Members\Models\Member;

class MemberObserver
{

    public function __construct(PasswordRepository $passwordRepository)
    {
        $this->passwordRepository = $passwordRepository;
    }
    /**
     * Listen to the User created event.
     *
     * @param  Member  $member
     * @return void
     */
    public function created(Member $member )
    {
        $this->passwordRepository->sendResetLinkEmail(['email'=>$member->user->email]);
    }


}
