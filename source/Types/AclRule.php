<?php
namespace Grout\Cyantree\AclModule\Types;

class AclRule
{
    public $roles;
    public $grants;

    public function __construct($roles = null, $grants = null)
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
