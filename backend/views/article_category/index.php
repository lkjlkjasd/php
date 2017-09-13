<?php
?>
<a href="<?=\yii\helpers\Url::to(['article_category/add'])?>">添加</a>
<table class="table table-bordered table-responsive">
    <tr>
        <td>id</td>
        <td>名称</td>
        <td>简介</td>
        <td>排序</td>
        <td>状态</td>
        <td>操作</td>
    </tr>
    <?php foreach($articles as $article):?>
     <tr>
         <td><?=$article->id?></td>
         <td><?=$article->name?></td>
         <td><?=$article->intro?></td>
         <td><?=$article->sort?></td>
         <td><?=$article->status==0?'隐藏':'正常';?></td>
         <td>
             <a href="<?=\yii\helpers\Url::to(['article_category/del','id'=>$article->id])?>">删除</a>
             <a href="<?=\yii\helpers\Url::to(['article_category/edit','id'=>$article->id])?>">修改</a>
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