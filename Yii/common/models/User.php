<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $token
 * * @property integer $role
 * @property integer $status
 */
class User extends ActiveRecord
{

    public static function tableName()
    {
        return 'user';
    }
    public function rules()
    {
        return [
            [['id','username','password','role','status'],'required'],
            [['id','role','status'],'integer'],
            [['username','password'],'string','max'=>255],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'role' => 'Role',
            'status' => 'Status',
        ];
    }
}