<?php
namespace Grout\Cyantree\AclModule\Types;

class AclLoginRequest
{
    public $username;
    public $password;

    /** @var AclLoginResponse */
    public $response;

    function __construct()
    {
        $this->response = new AclLoginResponse();
    }
}

class AclLoginResponse
{
    /** @var AclAccount */
    public $account;
}