<?php
namespace backend\controllers;
use backend\models\GoodsCategory;
use yii\data\Pagination;

class GoodsCategoryController extends \yii\web\Controller
{
    //添加商品分类
    public function actionAdd(){
        $model = new GoodsCategory();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //判断添加顶级分类还是非顶级分类(子分类)
                if($model->parent_id){
                    //非顶级分类(子分类)
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    //顶级分类
                    $model->makeRoot();
                }
                //$model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionTest(){
        //创建子分类
        $parent = GoodsCategory::findOne(['id'=>1]);
        $child = new GoodsCategory(['name' => '大家电']);
        $child->parent_id = 1;
        $child->prependTo($parent);
        //var_dump($model->getErrors());
        echo '操作成功';
    }
    //测试ztree
    public function actionZtree(){
        //不加载布局文件
        //$this->layout = false;
        $goodsCategories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();

        return $this->renderPartial('ztree',['goodsCategories'=>$goodsCategories]);
    }


    //展示
    public function actionIndex(){
        $query =GoodsCategory::find();
        // var_dump($models);exit;
        $pager =new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>2//分页显示几条
        ]);
        $goods = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['goods'=>$goods,'pager'=>$pager]);
        //分配数据到视图
    }


    public function actionDelete(){
        //分类下有子分类就不能删除
        $request=\Yii::$app->request;
        $id=$request->get('id');
        $model=GoodsCategory::findOne(['id'=>$id]);
        $sons=GoodsCategory::find()->where(['parent_id'=>$id])->asArray()->all();
        //var_dump($sons);die;
        if($sons){
            \Yii::$app->session->setFlash('danger','有儿子不能删除');
        }else{
            //删除
            $model->delete();
        }
        return $this->redirect('index');
    }



    //修改
    public function actionEdit($id){
        $model = GoodsCategory::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //判断添加顶级分类还是非顶级分类(子分类)
                if($model->parent_id){
                    //非顶级分类(子分类)
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    //顶级分类
                    $model->makeRoot();
                }
                //$model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
}