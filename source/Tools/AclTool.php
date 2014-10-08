<?php
namespace Grout\Cyantree\AclModule\Tools;

use Cyantree\Grout\Tools\ArrayTools;
use Grout\Cyantree\AclModule\Types\AclConfig;
use Grout\Cyantree\AclModule\Types\AclRole;
use Grout\Cyantree\AclModule\Types\AclSessionData;

class AclTool
{
    /** @var AclSessionData */
    private $sessionData;

    /** @var AclConfig */
    private $config;

    function __construct(AclConfig $config, AclSessionData $sessionData)
    {
        $this->config = $config;
        $this->sessionData = $sessionData;
    }

    public function isPermittedRole($permittedRole, $userRole = null)
    {
        if (!$userRole) {
            $userRole = $this->sessionData->account->role;
        }

        if (!$userRole) {
            return false;
        }

        /** @var AclRole $role */
        $role = $this->config->roles[$userRole];

        while($role->id != $permittedRole && $role->parent) {
            $role = $role->parent;
        }

        return $role->id == $permittedRole;
    }

    public function isGranted($permittedGrant, $userGrants = null, $userRole = null)
    {
        if ($userGrants) {
            $userGrants = ArrayTools::convertToKeyArray($userGrants);

        } elseif ($userRole) {
            $userGrants = $this->config->roles[$userRole]->getGrants();

        } elseif (!$userGrants) {
            $userRole = $this->sessionData->account->role;

            if (!$userRole) {
                return false;
            }

            $userGrants = $this->config->roles[$userRole]->getGrants();
        }

        return isset($userGrants['*']) || isset($userGrants[$permittedGrant]);
    }
}