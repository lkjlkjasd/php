<?php
namespace backend\controllers;

use backend\models\Article;
use backend\models\Article_category;
use backend\models\Article_detail;
use yii\web\Controller;
use yii\data\Pagination;

class ArticleController extends Controller{

    //添加
    public function actionAdd(){
        $articles=Article_category::find()->all();
        $king=[];
        foreach ($articles as $article){
            $king[$article->id] =$article->name;
        }

        //实例化一个model对象
        $model =new Article();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //保存数据
                $model->save();

                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article/index']);
            }
        }
        return $this->render('add',['model'=>$model,'king'=>$king]);
    }


    //展示列表
    public function actionIndex(){

        $king=Article_detail::find()->all();

        //var_dump($king[]);exit;
        $request = Article::find()->where(['status'=>1]);
        // var_dump($models);exit;
        $pager =new Pagination([
            'totalCount'=>$request->count(),
            'defaultPageSize'=>2//分页显示几条
        ]);
        $articles = $request->limit($pager->limit)->offset($pager->offset)->all();
        //var_dump($articles);exit;
        return $this->render('index',['articles'=>$articles,'pager'=>$pager,'king'=>$king]);
    }

    public function actionShow(){
        $requests = Article_detail::find()->all();
        return $this->render('show',['requests'=>$requests]);
    }

    //删除
    public function actionDel($id){
        $model = Article::findOne($id);
        $model->status=-1;
        $request = $model->save(false);
        if($request){
            \Yii::$app->session->setFlash('info','删除成功');
            return $this->redirect(['index']);
        }

    }

    //修改
    public function actionEdit($id){
        $articles=Article_category::find()->all();
        $king=[];
        foreach ($articles as $article){
            $king[$article->id] =$article->name;
        }

        //实例化一个model对象

        $model = Article::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //保存数据
                $model->save();

                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article/index']);
            }
        }
        return $this->render('edit',['model'=>$model,'king'=>$king]);
    }
}