<?php
/* @var $this yii\web\View */
?>

<a href="<?=\yii\helpers\Url::to(['goods-category/add'])?>">添加</a>
<table class="table table-bordered table-responsive">
    <tr>
        <td>id</td>
        <td>树id</td>
        <td>左值</td>
        <td>右值</td>
        <td>层级</td>
        <td>名称</td>
        <td>上级分类id</td>
        <td>简介</td>
        <td>操作</td>
    </tr>
    <?php foreach($goods as $good):?>
    <tr>
        <td><?=$good->id?></td>
        <td><?=$good->tree?></td>
        <td><?=$good->lft?></td>
        <td><?=$good->rgt?></td>
        <td><?=$good->depth?></td>
        <td><?=$good->name?></td>
        <td><?=$good->parent_id	?></td>
        <td><?=$good->intro?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['goods-category/delete','id'=>$good->id])?>">删除</a>
            <a href="<?=\yii\helpers\Url::to(['goods-category/edit','id'=>$good->id])?>">修改</a>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
    'nextPageLabel'=>'下一页'
]);