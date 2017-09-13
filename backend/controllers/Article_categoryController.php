<?php
namespace backend\controllers;
use backend\models\Article_category;
use yii\data\Pagination;
use yii\db\ActiveRecord;
use yii\web\Controller;

class Article_categoryController extends Controller{
    public function actionIndex(){
        $query =Article_category::find()->where(['status'=>1]);
        // var_dump($models);exit;
        $pager =new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>2//分页显示几条
        ]);
        $articles = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['articles'=>$articles,'pager'=>$pager]);

    }

    //添加
    public function actionAdd(){
        $model = new Article_category();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //保存数据
                $model->save();

                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['article_category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //删除
    public function actionDel($id){
        $model = Article_category::findOne($id);
        $model->status=-1;
        $request = $model->save(false);
        if($request){
            \Yii::$app->session->setFlash('info','删除成功');
            return $this->redirect(['index']);
        }

    }

    //修改
    public function actionEdit($id){
        $model = Article_category::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //保存数据
                $model->save();

                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['article_category/index']);
            }
        }
        return $this->render('edit',['model'=>$model]);
    }
}