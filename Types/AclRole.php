<?php
namespace Grout\Cyantree\AclModule\Types;

class AclRole
{
    public $id;

    /** @var AclRole */
    public $parent;

    public $isGuest = false;

    public $grants = array();

    public function __construct($id = null, array $grants = null)
    {
        $this->id = $id;

        if ($grants) {
            $this->addGrants($grants);
        }
    }

    public function addGrants(array $grants)
    {
        foreach ($grants as $grant) {
            $this->grants[$grant] = true;
        }
    }

    public function isRole($role)
    {
        if ($role == '-') {
            return false;
        }

        if ($role == '*' || $role == $this->id) {
            return true;

        } elseif ($this->parent) {
            return $this->parent->isRole($role);

        } else {
            return false;
        }
    }

    public function hasGrant($grant)
    {
        if ($grant == '-') {
            return false;
        }

        if ($grant == '*' || isset($this->grants['*']) || isset($this->grants[$grant])) {
            return true;

        } elseif ($this->parent) {
            return $this->parent->hasGrant($grant);

        } else {
            return false;
        }
    }

    public function getGrants()
    {
        $grants = $this->grants;

        $parentRole = $this->parent;

        while ($parentRole) {
            $grants = array_merge($grants, $parentRole->grants);
            $parentRole = $parentRole->parent;
        }

        return array_keys($grants);
    }
}
