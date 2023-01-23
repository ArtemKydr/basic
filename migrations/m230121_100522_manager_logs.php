<?php

use yii\db\Migration;

/**
 * Class m230121_100522_manager_logs
 */
class m230121_100522_manager_logs extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('manager_logs', [
            'id' => $this->primaryKey(),
            'personal_data_status'=>$this->boolean(),
            'manager_id'=>$this->string(),
            'user_id'=>$this->string(),
            'manager_fio'=>$this->string(),
            'user_fio'=>$this->string(),
            'document_status_change' => $this->string(),
            'comment'=>$this->string(),
            'datetime' => $this->string(),
        ]);
        $personal_data_status = "ALTER TABLE manager_logs ALTER personal_data_status SET DEFAULT 0";
        $this->execute($personal_data_status);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('manager_logs');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230121_100522_manager_logs cannot be reverted.\n";

        return false;
    }
    */
}
