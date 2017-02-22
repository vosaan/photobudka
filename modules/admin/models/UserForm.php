<?php

namespace app\modules\admin\models;
use yii\base\Model;
use Yii;

class UserForm extends Model
{
    public $username;
    public $password;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username', 'password'], 'string', 'length' => [3, 25]],
            ['password', 'validatePassword']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Login',
            'password' => 'Password',
        ];
    }

    public function validatePassword()
    {
        $user = User::findByUsername($this->username);

        if (!$user || !$user->checkUserPassword($this->password)){
            $this->addError('password', 'Wrong username or password');
        }
    }

    public function login(){
        return Yii::$app->user->login(User::findByUsername($this->username));
    }
}