<?php
namespace backend\controllers;

use backend\models\Brand;

use yii\data\Pagination;
use yii\filters\PageCache;
use yii\web\Controller;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;
class BrandController extends Controller{
    public function actionAdd(){
        $model =new Brand();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            //处理图片上传文件
            //$model->file = \yii\web\UploadedFile::getInstance($model,'file');
            if($model->validate()) {//>>>
                //移动文件
               // $file = '/upload/' . uniqid() . '.' . $model->file->getExtension();
                //保存文件
                //$model->file->saveAs(\Yii::getAlias('@webroot') . $file, false);

                //赋值给logo字段
//               $model->logo;

                //保存数据
                $model->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //展示
    public function actionIndex(){
        $query =Brand::find()->where(['status'=>1]);
       // var_dump($models);exit;
        $pager =new Pagination([
           'totalCount'=>$query->count(),
            'defaultPageSize'=>2//分页显示几条
        ]);
        $brands = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['brands'=>$brands,'pager'=>$pager]);
        //分配数据到视图
        //return $this->render('index',['models'=>$models]);
    }

    //修改
    public function actionEdit($id){
        $model = Brand::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            //处理图片上传文件
            //$model->file = \yii\web\UploadedFile::getInstance($model,'file');
            if($model->validate()) {
                //移动文件
               // $file = '/upload/' . uniqid() . '.' . $model->file->getExtension();
                //保存文件
                //$model->file->saveAs(\Yii::getAlias('@webroot') . $file, false);

                //赋值给logo字段
                //$model->logo = $file;

                //保存数据
                $model->save();
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('edit',['model'=>$model]);
    }

    //删除
//    public function actionDelete($id){
//        //查询列表数据
//        $author = Brand::find()->where(['id'=>$id])->one();
//        $author->delete();
//        return $this->redirect(['brand/index']);
//    }

    //删除
    public function actionDel($id){
        $model = Brand::findOne($id);
        $model->status=-1;
        $request = $model->save(false);
        if($request){
            \Yii::$app->session->setFlash('info','删除成功');
            return $this->redirect(['index']);
        }

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

//public function actionQiniu(){
//
//}
}

