<?php

use yii\db\Migration;

/**
 * Class m230125_182222_additional_files
 */
class m230125_182222_additional_files extends Migration
{
    public function safeUp()
    {
        $this->createTable('additional_files', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer(),
            'document_id'=>$this->string(),
            'fio'=>$this->string(),
            'expert'=>$this->string(),
            'review'=>$this->string(),
            'file_scan'=>$this->string(),
            'expert_name'=>$this->string(),
            'review_name'=>$this->string(),
            'file_scan_name'=>$this->string(),
            'expert_source'=>$this->string(),
            'review_source'=>$this->string(),
            'file_scan_source'=>$this->string(),
            ]);


    }


    public function safeDown()
    {
        $this->dropTable('additional_files');
    }
}
