<?php
namespace backend\models;

use yii\db\ActiveRecord;

class GoodsGallery extends ActiveRecord{
    public function rules(){
        //验证图片
        return [
            //验证图片
           ['path','string','max'=>'200']
        ];

    }
    public function attributeLabels(){
        return [

            'path'=>'上传图片',
        ];
    }
}