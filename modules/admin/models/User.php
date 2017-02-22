<?php

namespace app\modules\admin\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['username', 'password'],
            self::SCENARIO_UPDATE => ['username', 'password']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username'], 'string', 'max' => 25],
            [['password'], 'string', 'max' => 100],
            [['username', 'password'], 'string', 'min' => 3],
            ['username', 'validateUsernameUnique', 'on' => self::SCENARIO_CREATE]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
        ];
    }

    public function getSecurePassword()
    {
        return Yii::$app->getSecurity()->generatePasswordHash($this->password);
    }

    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    public function checkUserPassword($password){
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    public function validateUsernameUnique()
    {
        $user = self::findByUsername($this->username);

        if ($user){
            $this->addError('username', 'User with the same username already exist!');
        }
    }

    /******************************************************************************************************************/
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public function getId()
    {
        return $this->id;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {

    }

    public function getAuthKey()
    {

    }

    public function validateAuthKey($authKey)
    {

    }
}
