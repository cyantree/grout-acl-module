<?php
namespace Grout\Cyantree\AclModule\Types;

class AclLoginRequest
{
    public $username;
    public $password;

    /** @var AclRouteContext */
    public $context;

    /** @var AclLoginResponse */
    public $response;

    public function __construct()
    {
        $this->response = new AclLoginResponse();
    }
}
