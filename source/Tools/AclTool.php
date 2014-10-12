<?php
namespace Grout\Cyantree\AclModule\Tools;

use Grout\Cyantree\AclModule\Types\AclAccount;
use Grout\Cyantree\AclModule\Types\AclConfig;
use Grout\Cyantree\AclModule\Types\AclRule;
use Grout\Cyantree\AclModule\Types\AclSessionData;

class AclTool
{
    /** @var AclSessionData */
    private $sessionData;

    /** @var AclConfig */
    private $config;

    public function __construct(AclConfig $config, AclSessionData $sessionData)
    {
        $this->config = $config;
        $this->sessionData = $sessionData;
    }

    /** @return AclAccount */
    public function getAccount()
    {
        $account = $this->sessionData->account;

        if (!$account) {
            $account = $this->config->guestAccount;
        }

        return $account;
    }

    public function setAccount(AclAccount $account)
    {
        $this->sessionData->account = $account;
    }

    public function satisfies(AclRule $rule, AclAccount $account = null)
    {
        if (!$account) {
            $account = $this->getAccount();
        }

        return $rule->satisfies($account);
    }
}
