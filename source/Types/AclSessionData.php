<?php
namespace Grout\Cyantree\AclModule\Types;

class AclSessionData
{
    public $userId;
    public $username;
    public $userRole;

    public function login($username, $userId, $userRole)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->userRole = $userRole;
    }

    public function logout()
    {
        $this->username = $this->userId = $this->userRole = null;
    }
}