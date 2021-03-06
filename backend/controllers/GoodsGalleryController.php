<?php
namespace backend\controllers;

use backend\models\GoodsGallery;
use yii\web\Controller;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;
class GoodsGalleryController extends Controller{

    public function actionShow(){

        $model =new GoodsGallery();
        return $this->render('show',['model'=>$model]);
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
}