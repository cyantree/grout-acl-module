<?php
namespace Grout\Cyantree\AclModule;

use Cyantree\Grout\App\Module;
use Cyantree\Grout\App\Route;
use Grout\Cyantree\AclModule\Pages\AclPage;
use Grout\Cyantree\AclModule\Types\AclConfig;

class AclModule extends Module
{
    public function init()
    {
        $this->app->configs->setDefaultConfig($this->id, new AclConfig());
    }

    public function secureUrl($url, $name = null, $role = null, $grant = null)
    {
        $this->addRoute(
            $url,
            'Pages\AclPage',
            array('aclRoute' => true, 'role' => $role, 'grant' => $grant, 'name' => $name)
        );
    }

    public function secureUrlRecursive($url, $name = null, $role = null, $grant = null)
    {
        $this->addRoute(
                $url . '%%secureRecursive,.*%%',
                'Pages\AclPage',
                array('aclRoute' => true, 'role' => $role, 'grant' => $grant, 'name' => $name)
        );
    }

    public function whitelistUrl($url)
    {
        $this->addRoute($url, null, array('aclRoute' => false), 1);
    }

    public function whitelistUrlRecursive($url)
    {
        $this->addRoute($url . '%%whitelistRecursive,.*%%', null, array('aclRoute' => false), 1);
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

    public function routeRetrieved($task, $route)
    {
        /** @var Route $route */

        $aclRoute = $route->data->get('aclRoute');

        if ($aclRoute === null) {
            return true;
        }

        if ($task->data->get('aclWhitelisted')) {
            return false;
        }

        if ($aclRoute === false) {
            $task->data->set('aclWhitelisted', true);
            return false;
        }

        $task->data->set('aclModule', $this);

        return !AclPage::processAuthorization($this, $task, $route->data->get('role'), $route->data->get('grant'));
    }
}
