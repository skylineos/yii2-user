<?php

    require '/var/www/html/vendor/autoload.php';
    require '/var/www/html/vendor/yiisoft/yii2/Yii.php';
    $config = require '/var/www/html/config/test.php';

    (new yii\console\Application($config))->run();
