<?php
namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\Goods_intro;
use backend\models\GoodsBayCount;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\web\Controller;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;
class GoodsController extends Controller{

    public function actionAdd()
    {


        $goods =Brand::find()->all();
        $ling=[];
        foreach ($goods as $good){
            $ling[$good->id] =$good->name;
        }

        $model =new Goods();
        $intro =new GoodsIntro();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            //var_dump($model);exit;
            $intro->load($request->post());

           // var_dump($intro);exit;
            if($model->validate() && $intro->validate()){
                $day = date('Ymd');
                //查询表里的数据
                $count = GoodsDayCount::findOne(['day'=>$day]);
                if($count == null){
                    $count =new GoodsDayCount();//货号
                    $count->day=$day;
                    $count->count=0;
                    $count->save();
                }
                //$model->create_time=time();

                $model->sn=date('Ymd',time()).sprintf("%04d",$count->count+1);
                //var_dump($intro->goods_id);exit;
                $intro->goods_id=$model->id;
               // var_dump($intro->goods_id);
                $intro->save();
                $count->count++;
                $count->save();
                $model->save();
            }

            \Yii::$app->session->setFlash('success', '添加成功');
            return $this->redirect(['goods/index']);
        }
        return $this->render('add',['model'=>$model,'ling'=>$ling,'intro'=>$intro]);
    }



    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    // $action->output['fileUrl'] = $action->getWebUrl();//输出图片路径
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"

                    $config = [
                        'accessKey'=>'zD8mcr2qZ5nEhHX8ajRlGTKRHHGUyWnXDC6XCLLZ',
                        'secretKey'=>'3CMDHQEEo-L83DBbKpH3mkQtvXiybJvftjf8vEYW',
                        'domain'=>'http://ow1h4xvdf.bkt.clouddn.com/',
                        'bucket'=>'king',
                        'area'=>Qiniu::AREA_HUADONG
                    ];
                    $qiniu = new Qiniu($config);
                    $key = $action->getWebUrl();
                    //上传文件到七牛云
                    $file = $action->getSavePath();
                    $qiniu->uploadFile($file,$key);
                    $url = $qiniu->getLink($key);
                    $action->output['fileUrl'] = $url;
                },
            ],
        ];
    }
    //展示
    public function actionIndex(){

//        $query =Goods::find()->where(['status'=>1]);
//        // var_dump($models);exit;
//        $pager =new Pagination([
//            'totalCount'=>$query->count(),
//            'defaultPageSize'=>2//分页显示几条
//        ]);
//        $goods = $query->limit($pager->limit)->offset($pager->offset)->all();
//        return $this->render('index',['goods'=>$goods,'pager'=>$pager]);
       $where= \Yii::$app->request->get();
// print_r($where);die;

        //$conditions=implode(',',$where);
        //echo $name;die;
        $name=isset($where['name'])?$where['name']:'';
        $sn=isset($where['sn'])?$where['sn']:'';
        $sprice=isset($where['sprice'])?$where['sprice']:'';
        $tprice=isset($where['tprice'])?$where['tprice']:'';

        $pager=new Pagination([
            'totalCount'=> $models=Goods::find()->Where(['status'=>1])->andFilterWhere(['like','name',$name])->andFilterWhere(['like','sn',$sn])->andFilterWhere(['between','shop_price',$sprice,$tprice])->count(),
            'pageSize'=>4,
        ]);


        $goods=Goods::find()->Where(['status'=>1])->andFilterWhere(['like','name',$name])->andFilterWhere(['like','sn',$sn])->andFilterWhere(['between','shop_price',$sprice,$tprice])->orderBy('sort desc')->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['pager'=>$pager,'goods'=>$goods]);

    }

    //修改
    public function actionEdit($id)
    {


        $goods =Brand::find()->all();
        $ling=[];
        foreach ($goods as $good){
            $ling[$good->id] =$good->name;
        }
       $model = Goods::findOne(['id'=>$id]);
        $intro =GoodsIntro::findOne(['goods_id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            //var_dump($model);exit;
            $intro->load($request->post());
            // var_dump($intro);exit;
            if($model->validate()){
                $model->save();
                //var_dump($intro->goods_id);exit;
                $intro->goods_id=$model->id;
                // var_dump($intro->goods_id);
                $intro->save();
            }

            \Yii::$app->session->setFlash('success', '修改成功');
            return $this->redirect(['goods/index']);
        }
        return $this->render('add',['model'=>$model,'ling'=>$ling,'intro'=>$intro]);
    }

    //删除
    public function actionDelete($id){
        $model = Goods::findOne($id);
        $model->status=0;
        $request = $model->save(false);
        if($request){
            \Yii::$app->session->setFlash('info','删除成功');
            return $this->redirect(['index']);
        }

    }

}