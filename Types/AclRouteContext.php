<?php
namespace Grout\Cyantree\AclModule\Types;

class AclRouteContext
{
    public $name;
    public $loginPage;

    /** @var AclRule */
    public $rule;

    public $satisfied = null;

    function __construct(AclRule $rule, $name, $loginPage)
    {
        $this->rule = $rule;
        $this->name = $name;
        $this->loginPage = $loginPage;
    }
}
