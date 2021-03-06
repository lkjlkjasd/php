<?php
namespace backend\models;

use yii\db\ActiveRecord;

class GoodsDayCount extends ActiveRecord{

    public static function tableName()
    {
        return 'goods_day_count';
    }


    public function rules(){

        return [

            [['day','count'],'required'],
        ];

    }
    public function attributeLabels(){
        return [

            'day'=>'日期',
            'count'=>'商品数'
        ];
    }
}