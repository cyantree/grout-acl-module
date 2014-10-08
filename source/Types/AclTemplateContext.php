<?php
namespace Grout\Cyantree\AclModule\Types;

use Cyantree\Grout\App\Generators\Template\TemplateContext;
use Grout\Cyantree\AclModule\AclFactory;

class AclTemplateContext extends TemplateContext
{
    /** @var AclFactory */
    private $_factory;

    /** @return AclFactory */
    public function f()
    {
        if ($this->_factory === null) {
            $this->_factory = AclFactory::get($this->app);
        }

        return $this->_factory;
    }

    public function q()
    {
        return $this->f()->quick();
    }
}