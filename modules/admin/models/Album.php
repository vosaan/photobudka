<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "album".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $password
 * @property string $password_hash
 * @property string $description
 * @property string $create_date
 * @property string $edit_date
 * @property string $is_active
 * @property string $is_private
 */
class Album extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'album';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'password'], 'required'],
            [['description', 'is_active', 'is_private'], 'string'],
            [['create_date', 'edit_date'], 'safe'],
            [['title', 'slug'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'password' => 'Password',
            'description' => 'description',
            'create_date' => 'Create Date',
            'edit_date' => 'Edit Date',
            'is_active' => 'Is Active',
            'is_private' => 'Is Private',
        ];
    }

    public function getSecurePassword()
    {
        return Yii::$app->getSecurity()->generatePasswordHash($this->password);
    }
}
