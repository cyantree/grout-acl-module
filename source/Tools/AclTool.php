<?php
namespace Grout\Cyantree\AclModule\Tools;

use Cyantree\Grout\Tools\ArrayTools;
use Grout\Cyantree\AclModule\Types\AclAccount;
use Grout\Cyantree\AclModule\Types\AclConfig;
use Grout\Cyantree\AclModule\Types\AclRole;
use Grout\Cyantree\AclModule\Types\AclSessionData;

class AclTool
{
    /** @var AclSessionData */
    private $sessionData;

    /** @var AclConfig */
    private $config;

    public function __construct(AclConfig $config, AclSessionData $sessionData)
    {
        $this->config = $config;
        $this->sessionData = $sessionData;
    }

    public function isPermittedRole($permittedRole, $userRole = null)
    {
        if (!$userRole) {
            $userRole = $this->getAccount()->role;
        }

        if (!$userRole) {
            return false;
        }

        /** @var AclRole $role */
        $role = $this->config->roles[$userRole];

        while ($role->id != $permittedRole && $role->parent) {
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
            $userRole = $this->getAccount()->role;

            if (!$userRole) {
                return false;
            }

            $userGrants = $this->config->roles[$userRole]->getGrants();
        }

        return isset($userGrants['*']) || isset($userGrants[$permittedGrant]);
    }

    /** @return AclAccount */
    public function getAccount()
    {
        $account = $this->sessionData->account;

        if (!$account) {
            $account = $this->config->guestAccount;
        }

        return $account;
    }

    public function setAccount(AclAccount $account)
    {
        $this->sessionData->account = $account;
    }
}
