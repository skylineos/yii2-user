<?php

namespace app\modules\citizenAdmin\controllers;

use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function actionIndex()
    {
        throw new MethodNotAllowedHttpException;
    }
}
