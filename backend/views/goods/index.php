<?php


?>
    <form action="<?=\yii\helpers\Url::to(['goods/index'])?>" method="get">
        <input type="text" name="name" placeholder="商品名称">&emsp; <input type="text" name="sn" placeholder="货号">&emsp;
        <input type="text" name="sprice" placeholder="价格">&emsp;<input type="text" name="tprice" placeholder="到">
        <input type="submit" value="搜索" class="btn btn-success">
    </form>

<a href="<?=\yii\helpers\Url::to(['goods/add'])?>">添加</a>
<table class="table table-bordered table-responsive">
    <tr>
        <td>id</td>
        <td>商品名称</td>
        <td>货号</td>
        <td>LOGO图片</td>
        <td>商品分类id</td>
        <td>品牌分类</td>
        <td>市场价格</td>
        <td>商品价格</td>
        <td>库存</td>
        <td>是否在售</td>
        <td>状态</td>
        <td>排序</td>
        <tD>添加时间</tD>
        <td>浏览次数</td>
        <td>操作</td>
    </tr>
    <?php foreach($goods as $good):?>
    <tr>
        <td><?=$good->id?></td>
        <td><?=$good->name?></td>
        <td><?=$good->sn?></td>
        <td><img src="<?=$good->logo?>" width="100"/></td>
        <td><?=$good->goods_category_id?></td>
        <td><?=$good->brand_id?></td>
        <td><?=$good->market_price?></td>
        <td><?=$good->shop_price?></td>
        <td><?=$good->stock?></td>
        <td><?=$good->is_on_sale==0?'下架':'在售';?></td>
        <td><?=$good->status==0?'回收站':'正常';?></td>
        <td><?=$good->sort?></td>
        <td><?=date('Y-m-d H:i:s',$good->create_time)?></td>
        <tD><?=$good->view_times?></tD>
        <td>
            <a href="<?=\yii\helpers\Url::to(['goods-gallery/show'])?>">相册</a>
            <a href="<?=\yii\helpers\Url::to(['goods/edit','id'=>$good->id])?>">修改</a>
            <a href="<?=\yii\helpers\Url::to(['goods/delete','id'=>$good->id])?>">删除</a>
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