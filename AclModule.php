<?php
namespace Grout\Cyantree\AclModule;

use Cyantree\Grout\App\Module;
use Cyantree\Grout\App\Route;
use Cyantree\Grout\App\Task;
use Cyantree\Grout\Event\Event;
use Cyantree\Grout\Tools\AppTools;
use Grout\Cyantree\AclModule\Pages\AclPage;
use Grout\Cyantree\AclModule\Types\AclConfig;
use Grout\Cyantree\AclModule\Types\AclRouteContext;
use Grout\Cyantree\AclModule\Types\AclRule;

class AclModule extends Module
{
    /** @var AclFactory */
    private $factory;

    public function factory()
    {
        if ($this->factory === null) {
            $this->factory = AclFactory::get($this->app, $this);
        }

        return $this->factory;
    }

    public function init()
    {
        $this->app->configs->setDefaultConfig($this->id, new AclConfig());
    }

    public function secureUrl($url, AclRule $rule, $name = null, $loginPage = null)
    {
        $route = $this->addRoute($url);
        $this->secureRoute($route, $rule, $name, $loginPage);
    }

    public function secureUrlRecursive($url, AclRule $rule, $name = null, $loginPage = 0)
    {
        $route = $this->addRoute($url . '%%secureRecursive,.*%%');
        $this->secureRoute($route, $rule, $name, $loginPage);
    }

    public function secureRoute(Route $route, AclRule $rule, $name = null, $loginPage = null)
    {
        $route->priority = $rule->priority;
        $route->data->set($this->id, new AclRouteContext($rule, $name, $loginPage));
        $route->events->join('retrieved', array($this, 'onRouteRetrieved'));
    }

    public function onRouteRetrieved(Event $event)
    {
        /** @var Route $route */
        $route = $event->context['route'];

        /** @var Task $task */
        $task = $event->context['task'];

        if ($task->data->get($this->id . 'result')) {
            return;
        }

        /** @var AclRouteContext $context */
        $context = $route->data->get($this->id);

        if (!$context) {
            return;
        }

        $context->satisfied = AclPage::processAuthorization($this, $task, $context->rule);

        if (!$context->satisfied) {
            if ($loginPage = $context->loginPage) {
                $loginPageContext = $this->app->decodeContext($loginPage, $task->module, $task->plugin);

                $module = $loginPageContext->module;
                $page = $loginPageContext->uri;

            } else {
                $module = $this;
                $page = 'Pages\AclPage';
            }

            $event->data = $module->addRoute('', $page, $route->data->getData(), 0, false);
            $event->stopPropagation = true;
        }
    }

    public function addLogoutUrl($url)
    {
        $this->addNamedRoute('logout', $url, 'Pages\LogoutPage');
    }

    public function getLogoutUrl()
    {
        $route = $this->getRoute('logout', false);

        return $route ? $route->getUrl() : null;
    }

    public function routeRetrieved(Task $task, Route $route)
    {
        /** @var AclRouteContext $context */
        $context = $route->data->get($this->id);

        $resultKey = $this->id . '.result';
        $taskResult = $task->data->get($resultKey);

        if ($taskResult === null) {
            $taskResult = $context->satisfied;
            $task->data->set($resultKey, $taskResult);
        }

        return $taskResult !== true;
    }
}
