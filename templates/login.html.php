<?php
use Cyantree\Grout\App\Generators\Template\TemplateContext;
use Cyantree\Grout\Tools\StringTools;

/** @var $this TemplateContext */
?>
<h1>Access denied</h1>
<p>
    Login to access <strong>“<?= $this->in->get('name') ?>”</strong>.
</p>
<form action="<?= StringTools::escapeHtml($this->task->request->url) ?>" method="post">
    <div class="element">
        <label for="username">Username:</label>
        <input id="username" type="text" name="username" value="<?= StringTools::escapeHtml($this->in->get('username')) ?>">
    </div>
    <div class="element">
        <label for="password">Password:</label>
        <input id="password" type="password" name="password">
    </div>
    <div>
        <input type="submit" name="login" value="Login">
    </div>
</form>
