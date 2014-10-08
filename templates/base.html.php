<?php
use Cyantree\Grout\Tools\StringTools;
use Grout\Cyantree\AclModule\Types\AclTemplateContext;

/** @var $this AclTemplateContext */
$q = $this->q();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $q->e($this->task->data->get('pageTitle', 'ACL')) ?></title>
    <meta charset="UTF-8">
    <base href="<?= $q->e($this->app->url) ?>">
    <style>
        body {
            font-family: Verdana, Arial, sans-serif;
            background: #eee;
            text-align: center;
        }

        .main {
            box-sizing: border-box;
            background: white;
            border-radius: 10px;
            margin: 20px auto;
            padding: 20px;
            display: inline-block;
            text-align: left;
        }

        h1 {
            margin: 0 0 10px 0;
        }

        p {
            margin: 30px 0;
        }

        label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
        }

        input {
            display: inline-block;
            width: 300px;
            border: solid 1px #ddd;
            padding: 5px;
        }

        input[type=submit] {
            background: #888;
            color: white;
            margin: 20px auto 0 auto;
            display: block;
            width: 200px;
            cursor: pointer;
        }

        form .element {
            margin: 10px 0;
        }
    </style>
</head>
<body>
<div class="main">
    <?= $this->in->get('content') ?>
</div>
</body>
</html>