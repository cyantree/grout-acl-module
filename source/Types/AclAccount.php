<?php
namespace Grout\Cyantree\AclModule\Types;

class AclAccount
{
    public $username;
    public $password;
    public $role;

    public $data;

    function __construct($username = null, $password = null, $role = null, $data = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
        $this->data = $data;
    }
}