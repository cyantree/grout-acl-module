<?php
namespace Grout\Cyantree\AclModule\Types;

class AclConfig
{
    /** @var AclRole[] */
    public $roles = array();

    public $defaultRole;
    public $defaultGrants = array();

    public $accounts = array();

    public $loginTemplate = 'login.html';
    public $logoutTemplate = 'logout.html';
    public $baseTemplate = 'base.html';

    /** @return AclRole */
    public function addRole(AclRole $role, $parentRoleOrId = null)
    {
        $this->roles[$role->id] = $role;

        if ($parentRoleOrId) {
            /** @var $parentRole AclRole */
            if (is_string($parentRoleOrId)) {
                $parentRole = $this->roles[$parentRoleOrId];

            } else {
                $parentRole = $parentRoleOrId;
            }

            if ($parentRole == $role) {
                throw new \Exception('Role equals parent role.');
            }

            $role->parent = $parentRole;
        }

        return $role;
    }

    public function addAccount(AclAccount $account)
    {
        $this->accounts[$account->username] = $account;
    }
}