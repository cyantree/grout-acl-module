<?php
use Grout\Cyantree\AclModule\Types\AclTemplateContext;

/** @var $this AclTemplateContext */

$q = $this->q();

$this->task->data->set('pageTitle', $q->t('Logout'));
?>
<h1><?= $q->et('Logout') ?></h1>
<p>
    <?= $q->et('You have been logged out!') ?>
</p>