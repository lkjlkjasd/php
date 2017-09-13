<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Brand extends ActiveRecord{

    public $PageCache;
   // public $file;
    public function rules()
    {
        return [

            [['name','intro','sort','status'],'required'],
            //判断图片
            ['logo','string','max'=>'200'],//>>
            //验证图片
           // ['file','file','extensions'=>['jpg','png','gif']],
        ];
    }
    public function attributeLabels(){
        return [

            'name'=>'名称',
            'intro'=>'简介',
            'sort'=>'排序',
            'logo'=>'图片',
            'status'=>'状态'
        ];
    }
}

