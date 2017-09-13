<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Article_detail extends ActiveRecord{
    //public $PageCache;
    public function rules()
    {
        return [

            [['content'],'required']
        ];
    }
    public function attributeLabels(){

        return [

            'content'=>'简介',
        ];
    }
}