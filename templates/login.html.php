<?php
use Grout\Cyantree\AclModule\Types\AclTemplateContext;

/** @var $this AclTemplateContext */

$q = $this->q();

$this->task->data->set('pageTitle', $q->t('Access denied'));
?>
<h1><?= $q->et('Access denied') ?></h1>
<p>
    <?= $q->p($q->t('Login to access <strong>“%h:name%”</strong>.'), array('name' => $q->e($this->in->get('name')))) ?>
</p>
<?php
if ($this->in->get('error')) {
    ?>
    <p class="error">
    <?= $q->et('You are not allowed to access this page. Please check your credentials.') ?>
    </p>
    <?php
}
?>
<form action="<?= $q->e($this->task->request->url) ?>" method="post">
    <div class="element">
        <label for="username"><?= $q->et('Username:') ?></label>
        <input id="username" type="text" name="username" value="<?= $q->e($this->in->get('username')) ?>">
    </div>
    <div class="element">
        <label for="password"><?= $q->et('Password:') ?></label>
        <input id="password" type="password" name="password">
    </div>
    <div>
        <input type="submit" name="login" value="<?= $q->et('Login') ?>">
    </div>
</form>
