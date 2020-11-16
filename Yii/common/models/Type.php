<?php
namespace common\models;

use Yii;
use yii\base\Model;


class Type extends Model
{
    public static function tableName()
    {
        return 'type';
    }
    public function rules()
    {
        return [
            [['id'],'required'],
            [['id'],'integer'],
            [['typename'],'string','max'=>255],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'typename'=>'Typename',
        ];
    }
}