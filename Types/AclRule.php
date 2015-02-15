<?php
namespace Grout\Cyantree\AclModule\Types;

class AclRule
{
    public $roles;
    public $grants;
    public $priority;

    public function __construct($roles = null, $grants = null, $priority = 0)
    {
        if ($roles) {
            if (!is_array($roles)) {
                $roles = array($roles);

            }

            $this->roles = $roles;

        } else {
            $this->roles = array();
        }


        if ($grants) {
            if (!is_array($grants)) {
                $grants = array($grants);

            }

            $this->grants = $grants;

        } else {
            $this->grants = array();
        }

        $this->priority = $priority;
    }

    public function satisfies(AclAccount $account)
    {
        /** @var AclRole $accountRole */
        $accountRole = $account->role;

        foreach ($this->roles as $role) {

            if ($accountRole->isRole($role)) {
                return true;
            }
        }

        foreach ($this->grants as $grant) {
            if ($accountRole->hasGrant($grant)) {
                return true;
            }
        }

        return false;
    }
}
