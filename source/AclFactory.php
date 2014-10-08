<?php
namespace Grout\Cyantree\AclModule;

use Cyantree\Grout\App\App;
use Cyantree\Grout\App\Generators\Template\TemplateGenerator;
use Cyantree\Grout\App\GroutFactory;
use Cyantree\Grout\Translation\DummyTranslator;
use Cyantree\Grout\Translation\Translator;
use Grout\AppModule\AppFactory;
use Grout\Cyantree\AclModule\Tools\AclTool;
use Grout\Cyantree\AclModule\Tools\AclQuick;
use Grout\Cyantree\AclModule\Types\AclConfig;
use Grout\Cyantree\AclModule\Types\AclSessionData;
use Grout\Cyantree\AclModule\Types\AclTemplateContext;

class AclFactory extends AppFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    /** @return AclFactory */
    public static function get(App $app = null, $context = null, $module = 'Cyantree\AclModule')
    {
        return GroutFactory::_getInstance($app, __CLASS__, $context, $module);
    }

    /** @return AclSessionData */
    public function sessionData()
    {
        $sessionData = parent::sessionData();

        $tool = $sessionData->get($this->module->id);

        if ($tool === null) {
            $tool = new AclSessionData();

            $config = $this->config();
            $tool->login($config->guestAccount);

            $sessionData->set($this->module->id, $tool);
        }

        return $tool;
    }

    /** @return AclConfig */
    public function config()
    {
        if (!($tool = $this->_getAppTool(__FUNCTION__, __CLASS__))) {
            $tool = $this->app->configs->getConfig($this->module->id);

            $this->_setAppTool(__FUNCTION__, $tool);
        }

        return $tool;
    }

    /** @return AclTool */
    public function acl()
    {
        if (!($tool = $this->_getAppTool(__FUNCTION__, __CLASS__))) {
            $tool = new AclTool($this->config(), $this->sessionData());

            $this->_setAppTool(__FUNCTION__, $tool);
        }

        return $tool;
    }

    public function templates()
    {
        if (!($tool = $this->_getAppTool(__FUNCTION__, __CLASS__))) {
            $tool = new TemplateGenerator();
            $tool->app = $this->app;
            $tool->setTemplateContext(new AclTemplateContext());
            $tool->defaultModule = $this->module;

            $this->_setAppTool(__FUNCTION__, $tool);
        }

        return $tool;
    }

    /** @return AclQuick */
    public function quick()
    {
        if (!($tool = $this->_getAppTool(__FUNCTION__, __CLASS__))) {
            $tool = new AclQuick($this->app);
            $tool->translator = $this->translator();
            $tool->translatorDefaultTextDomain = $this->module->id;

            $this->_setAppTool(__FUNCTION__, $tool);
        }

        return $tool;
    }

    /** @return Translator */
    public function translator()
    {
        if (!($tool = $this->_getAppTool(__FUNCTION__, __CLASS__))) {
            $tool = new DummyTranslator();

            $this->_setAppTool(__FUNCTION__, $tool);
        }

        return $tool;
    }
}