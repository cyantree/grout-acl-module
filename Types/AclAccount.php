<?php
namespace Grout\Cyantree\AclModule\Types;

class AclAccount
{
    public $username;
    public $password;

    /** @var AclRole */
    public $role;

    public $data;

    public function __construct($username = null, $password = null, AclRole $role = null, $data = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
        $this->data = $data;
    }

    public function checkPassword($password = null)
    {
        return $password !== null && $password === $this->password;
    }
}
