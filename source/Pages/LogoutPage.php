<?php
namespace Grout\Cyantree\AclModule\Pages;

use Cyantree\Grout\App\Generators\Template\TemplateGenerator;
use Cyantree\Grout\App\Page;
use Cyantree\Grout\App\Task;
use Cyantree\Grout\Tools\ArrayTools;
use Grout\Cyantree\AclModule\AclFactory;
use Grout\Cyantree\AclModule\AclModule;
use Grout\Cyantree\AclModule\Types\AclAccount;
use Grout\Cyantree\AclModule\Types\AclLoginRequest;

class LogoutPage extends Page
{
    public function parseTask()
    {
        $f = AclFactory::get($this->app);
        $f->sessionData()->logout();

        $this->setResult($f->templates()->load($f->config()->logoutTemplate, null, $f->config()->baseTemplate)->content);
    }
}