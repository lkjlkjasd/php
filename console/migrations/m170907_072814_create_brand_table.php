<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m170907_072814_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->comment('名称'),
            'intro' => $this->text()->comment('简介'),
            'logo' => $this->string(255)->comment('图片'),
            'sort' => $this->string(11)->comment('排序'),
            'status' => $this->string(2)->comment('状态')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
