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

    public function secureUrl($url, AclRule $rule, $name = null, $loginRoute = null)
    {
        $route = $this->addRoute($url, null, null, $rule->priority);
        return $this->finalizeSecureRoute($route, $rule, $name, $loginRoute);
    }

    public function secureUrlRecursive($url, AclRule $rule, $name = null, $loginRoute = null)
    {
        $route = $this->addRoute($url . '%%secureRecursive,.*%%', null, null, $rule->priority);
        return $this->finalizeSecureRoute($route, $rule, $name, $loginRoute);
    }

    public function secureRoute(Route $route, AclRule $rule, $name = null, $loginRoute = null)
    {
        $priority = $this->priority - $route->module->priority + $rule->priority;
        $newRoute = $route->module->addRoute($route->getMatchUrl(), null, null, $priority);

        return $this->finalizeSecureRoute($newRoute, $rule, $name, $loginRoute);
    }

    private function finalizeSecureRoute(Route $route, AclRule $rule, $name = null, $loginRoute = null)
    {
        $context = new AclRouteContext($rule, $name, $loginRoute);
        $route->data->set($this->id, $context);
        $route->events->join('retrieved', array($this, 'onRouteRetrieved'));

        return $context;
    }

    public function onRouteRetrieved(Event $event)
    {
        /** @var Route $route */
        $route = $event->context['route'];

        /** @var Task $task */
        $task = $event->context['task'];

        if ($task->data->has($this->id . '.result')) {
            $event->data = false;
            return;
        }

        /** @var AclRouteContext $context */
        $context = $route->data->get($this->id);

        if (!$context) {
            return;
        }

        $context->satisfied = AclPage::processAuthorization($this, $task, $context->rule);

        $task->data->set($this->id . '.result', $context->satisfied);

        if ($context->satisfied) {
            $event->data = false; // Skip route because it is only a dummy for ACL checking

        } else {
            $loginRoute = $context->loginRoute;

            if (!$loginRoute) {
                $loginRoute = $this->getDefaultLoginRoute();
            }

            // Redirect to login route
            $event->data = array(
                'route' => $loginRoute,
                'vars' => array($this->id => $context)
            );
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

    private function getDefaultLoginRoute()
    {
        if ($this->hasRoute('login')) {
            return $this->getRoute('login');

        } else {
            return $this->addNamedRoute('login', '', 'Pages\AclPage', null, 0, false);
        }


    }
}
