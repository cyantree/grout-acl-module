<?php
namespace Grout\Cyantree\AclModule\Types;

class AclConfig
{
    /** @var AclRole[] */
    public $roles = array();

    /** @var AclRole */
    public $guestRole;

    /** @var AclAccount */
    public $guestAccount;

    public $accounts = array();

    public $loginTemplate = 'login.html';
    public $logoutTemplate = 'logout.html';
    public $baseTemplate = 'base.html';

    /** @return AclRole */
    public function addRole(AclRole $role, $parentRoleOrId = null)
    {
        $this->roles[$role->id] = $role;

        if (!$parentRoleOrId) {
            $parentRoleOrId = $this->guestAccount;
        }

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

    public function setGuestRole(AclRole $role)
    {
        $this->addRole($role);
        $this->guestRole = $role;
    }

    public function setGuestAccount(AclAccount $account)
    {
        $this->addAccount($account);
        $account->role = $this->guestRole->id;
        $this->guestAccount = $account;
    }

    public function addAccount(AclAccount $account)
    {
        $this->accounts[$account->username] = $account;
    }
}