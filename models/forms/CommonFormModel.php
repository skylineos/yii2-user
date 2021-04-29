<?php

namespace skyline\yii\user\models\forms;

use yii\base\Model;
use skyline\yii\user\models\User;

class CommonFormModel extends Model
{
    private $user = null;

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->user === null) {
            $this->user = User::findByEmail($this->username);
        }

        return $this->user;
    }
}
