<?php
namespace backend\controllers;

use backend\models\Article;
use backend\models\Article_detail;
//use backend\models\Article_detail;
use yii\web\Controller;
use yii\data\Pagination;

class Article_detailController extends Controller{


    //添加数据
    public function actionAdd(){


        $articles=Article::find()->all();
        $king=[];
        foreach ($articles as $article){
            $king[$article->id] =$article->name;
        }
        //实例化一个对象
        $model = new Article_detail();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //保存数据
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article_detail/index']);
            }
        }
        return $this->render('add',['model'=>$model,'king'=>$king]);
    }

    //展示表单
    public function actionIndex(){
        $request = Article_detail::find();
        // var_dump($models);exit;
        $pager =new Pagination([
            'totalCount'=>$request->count(),
            'defaultPageSize'=>2//分页显示几条
        ]);
        $articles = $request->limit($pager->limit)->offset($pager->offset)->all();
        //var_dump($articles);exit;
        return $this->render('index',['articles'=>$articles,'pager'=>$pager]);

    }

}