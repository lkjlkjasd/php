<?php
?>
<a href="<?=\yii\helpers\Url::to(['brand/add'])?>">添加</a>
<table class="table table-bordered table-responsive">
    <tr>
        <th>id</th>
        <th>名称</th>
        <th>简介</th>
        <th>LOGO图片</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($brands as $brand):?>
    <tr data-id="<?=$brand->id?>">
        <td><?=$brand->id?></td>
        <td><?=$brand->name?></td>
        <td><?=$brand->intro?></td>
        <td><img src="<?=$brand->logo?>" width="100"/></td>
        <td><?=$brand->sort?></td>
        <td><?=$brand->status==0?'隐藏':'正常';?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['brand/del','id'=>$brand->id])?>">删除</a>
            <a href="<?=\yii\helpers\Url::to(['brand/edit','id'=>$brand->id])?>">修改</a>
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
?>
<?php
$del_ulr = \yii\helpers\Url::to(['brand/del']);
    //注册js代码
   $this->registerJS(new \yii\web\JsExpression(
       <<<JS
    $(".del_btn").click(function(){
       var parentData=$(this).parent().parent().attr("data-id")
       var parent = $(this).parent().parent();
            $.post(
                    '$del_ulr',
                    {
                        id:parentData
                    },
                    function(data) {
                      if(data=="success"){
                      parent.css("display","none")
                      alert('成功')
                      }else{
                        alert('失败')
                      }
                    },"json"
            )
        }
    );
JS
));






