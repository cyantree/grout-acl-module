<?php
namespace Grout\Cyantree\AclModule\Types;

class AclSessionData
{
    /** @var AclAccount */
    public $account;

    public function login(AclAccount $account)
    {
        $this->account = $account;
    }

    public function logout()
    {
        $this->account = null;
    }
}