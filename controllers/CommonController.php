<?php
/**
 * Created by PhpStorm.
 * User: Vo
 * Date: 22.02.2017
 * Time: 17:27
 */

namespace app\controllers;
use yii\web\Controller;

class CommonController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
}