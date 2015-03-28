<?php
namespace Grout\Cyantree\AclModule\Types;

class AclRouteContext
{
    public $name;
    public $loginRoute;

    /** @var AclRule */
    public $rule;

    public $satisfied = null;

    function __construct(AclRule $rule, $name, $loginRoute)
    {
        $this->rule = $rule;
        $this->name = $name;
        $this->loginRoute = $loginRoute;
    }
}
