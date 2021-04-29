<?php

namespace skyline\yii\user\models\forms;

use yii\base\Model;
use skyline\yii\user\models\User;

class CommonFormModel extends Model
{
    private $_user = null;

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByEmail($this->username);
        }

        return $this->_user;
    }
}
