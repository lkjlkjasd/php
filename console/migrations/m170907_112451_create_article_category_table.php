<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m170907_112451_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->comment('名称'),
            'intro' => $this->text()->comment('简介'),
            'sort' => $this->string(11)->comment('排序'),
            'status' => $this->string(2)->comment('状态')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
