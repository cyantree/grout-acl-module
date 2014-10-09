<?php
namespace Grout\Cyantree\AclModule\Types;

use Cyantree\Grout\App\Generators\Template\TemplateContext;
use Grout\Cyantree\AclModule\AclFactory;

class AclTemplateContext extends TemplateContext
{
    /** @var AclFactory */
    private $factory;

    /** @return AclFactory */
    public function f()
    {
        if ($this->factory === null) {
            $this->factory = AclFactory::get($this->app);
        }

        return $this->factory;
    }

    public function q()
    {
        return $this->f()->quick();
    }
}
