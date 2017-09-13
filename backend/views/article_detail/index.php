<?php
?>
<a href="<?=\yii\helpers\Url::to(['article_detail/add'])?>">添加</a>
<table class="table table-bordered table-responsive">
    <tr>
        <td>id</td>
        <td>简介</td>
        <td>操作</td>
    </tr>
    <?php foreach($articles as $article):?>
    <tr>
        <td><?=$article->article_id?></td>
        <td><?=$article->content?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['article/delete','id'=>$article->article_id])?>">删除</a>
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