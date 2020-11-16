<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;


class Question extends ActiveRecord
{
    public static function tableName()
    {
        return 'Question';
    }

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['content','title','type'], 'text'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title'=>'Title',
            'content'=>'Content',
            'type'=>'Type',
        ];
    }
}