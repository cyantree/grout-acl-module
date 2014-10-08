<?php
namespace Grout\Cyantree\AclModule\Types;

class AclRole
{
    public $id;

    /** @var AclRole */
    public $parent;

    /** @var AclRole[] */
    public $roles = array();

    public $grants = array();

    function __construct($id = null, array $grants = null)
    {
        $this->id = $id;

        if ($grants) {
            $this->addGrants($grants);
        }
    }


    /** @return AclRole */
//    public function addRole(AclRole $role)
//    {
//        $this->roles[] = $role;
//        $role->parent = $this;
//
//        return $role;
//    }

    public function addGrants(array $grants)
    {
        foreach ($grants as $grant) {
            $this->grants[$grant] = true;
        }
    }

    public function getGrants()
    {
        $grants = $this->grants;

        $parentRole = $this->parent;

        while($parentRole) {
            $grants = array_merge($grants, $parentRole->grants);
            $parentRole = $parentRole->parent;
        }

        return $grants;
    }
}