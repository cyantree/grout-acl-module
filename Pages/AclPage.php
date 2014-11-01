<?php
namespace Grout\Cyantree\AclModule\Pages;

use Cyantree\Grout\App\Generators\Template\TemplateGenerator;
use Cyantree\Grout\App\Page;
use Cyantree\Grout\App\Task;
use Cyantree\Grout\App\Types\ResponseCode;
use Cyantree\Grout\Tools\ArrayTools;
use Grout\Cyantree\AclModule\AclFactory;
use Grout\Cyantree\AclModule\AclModule;
use Grout\Cyantree\AclModule\Types\AclAccount;
use Grout\Cyantree\AclModule\Types\AclLoginRequest;
use Grout\Cyantree\AclModule\Types\AclRule;

class AclPage extends Page
{
    public static function processAuthorization(AclModule $module, Task $task, AclRule $rule)
    {
        $f = AclFactory::get($task->app, null, $module);

        $success = $f->acl()->satisfies($rule);

        if (!$success) {
            list($username, $password) = $task->request->post->getMultiple(array('username', 'password'), false);

            if ($username) {
                // Check inline registered accounts
                $config = $f->config();

                /** @var AclAccount $account */
                $account = ArrayTools::get($config->accounts, $username);
                if ($account) {
                    $success = $f->acl()->satisfies($rule, $account);

                    if ($success) {
                        $f->sessionData()->login($account);
                    }

                } else {
                    $request = new AclLoginRequest();
                    $request->username = $username;
                    $request->password = $password;

                    $module->events->trigger('login', $request);

                    if ($request->response->account) {
                        $f->sessionData()->login($request->response->account);

                        $success = true;
                    }
                }
            }
        }

        return $success;
    }

    public function parseTask()
    {
        $f = AclFactory::get($this->app);

        $aclConfig = $this->task->vars->asFilter($this->module->id);

        $this->setResult($f->templates()->load($f->config()->loginTemplate, array(
                          'url' => $this->task->url,
                          'name' => $aclConfig->get('name'),
                          'username' => $this->task->request->post->get('username'),
                          'error' => $this->task->request->method == 'POST'
                    ), $f->config()->baseTemplate)->content, null, ResponseCode::CODE_403);
    }
}
