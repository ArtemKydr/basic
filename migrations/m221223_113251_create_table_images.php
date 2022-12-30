<?php

use yii\db\Migration;

/**
 * Class m221223_113251_create_table_images
 */
class m221223_113251_create_table_images extends Migration
{

    public function up()
    {
        $this->createTable('images',['id'=>$this->primaryKey(),'name'=>$this->string(),'source'=>$this->text(),'created_at'=>$this->string()]);
    }

    public function down()
    {
        $this->dropTable('images');
    }

}
