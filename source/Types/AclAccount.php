<?php
namespace Grout\Cyantree\AclModule\Types;

class AclAccount
{
    public $userId;
    public $username;
    public $password;
    public $role;

    function __construct($username = null, $password = null, $role = null, $id = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
        $this->userId = $id;
    }
}