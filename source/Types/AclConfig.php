<?php
namespace Grout\Cyantree\AclModule\Types;

class AclConfig
{
    /** @var AclRole[] */
    public $roles = array();

    /** @var AclAccount */
    public $guestAccount;

    /** @var AclRole */
    public $guestRole;

    /** @var AclAccount[] */
    public $accounts = array();

    public $loginTemplate = 'login.html';
    public $logoutTemplate = 'logout.html';
    public $baseTemplate = 'base.html';

    function __construct()
    {
        $this->guestRole = $this->addRole(new AclRole('guest'));
        $this->guestAccount = $this->addAccount(new AclAccount('guest', '', 'guest'));
    }


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

        return $account;
    }
}
