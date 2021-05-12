<?php

    require '/app/vendor/autoload.php';
    require '/app/vendor/yiisoft/yii2/Yii.php';
    $config = require '/app/config/test.php';

    (new yii\console\Application($config))->run();
