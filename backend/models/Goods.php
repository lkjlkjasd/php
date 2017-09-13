<?php
namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Goods extends ActiveRecord{
    public $PageCache;
    public function rules(){

        return [

            [['name','goods_category_id','brand_id','market_price','shop_price','stock'
                ,'is_on_sale','status','sort'],'required'],
            //验证图片
            ['logo','string','max'=>'200']
        ];

    }
    public function attributeLabels(){
        return [

            'name'=>'商品名称',
            'goods_category_id'=>'商品分类id',
            'logo'=>'商品图片',
            'brand_id'=>'品牌分类',
            'market_price'=>'市场价格',
            'shop_price'=>'商品价格',
            'stock'=>'库存',
            'is_on_sale'=>'是否在售',
            'status'=>'状态',
            'sort'=>'排序',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time'],
                    //ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],


                ],
            ],
        ];
    }



}