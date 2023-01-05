<?php

use yii\db\Migration;

/**
 * Class m230105_112513_create_table_user
 */
class m230105_112513_create_table_user extends Migration
{
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'email' => $this->string(),
            'password' => $this->string(),
            'fio' => $this->string(),
            'city' => $this->string(),
            'organization' => $this->string(),
            'role' => 'ENUM("user", "admin","manager")',
            'is_active' => 'ENUM("active", "blocked", "deleted")',
            'phone'=>$this->string(),
            'secret_key'=>$this->string(),
        ]);
        $sql_status = "ALTER TABLE user ALTER is_active SET DEFAULT 'active'";
        $sql_role = "ALTER TABLE user ALTER role SET DEFAULT 'user'";
        $this->execute($sql_status);
        $this->execute($sql_role);

    }


    public function safeDown()
    {
        $this->dropTable('user');
    }

}
