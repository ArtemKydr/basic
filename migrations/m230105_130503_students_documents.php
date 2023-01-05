<?php

use yii\db\Migration;

/**
 * Class m230105_130503_students_documents
 */
class m230105_130503_students_documents extends Migration
{
    public function safeUp()
    {
        $this->createTable('documents', [
            'id' => $this->primaryKey(),
            'authors' => $this->string(),
            'nr' => $this->string(),
            'university' => $this->string(),
            'sourcebook' => $this->string(),
            'document_status' => 'ENUM("Send for revision",
"Reject",
"Send to Print",
"In the draft",
"The article did not pass the originality test",
"The article was checked for originality",
"On proofreading",
"For revision", 
"The article was accepted",
"In processing",
"Last change")',
        ]);
        $sql_document_status = "ALTER TABLE documents ALTER document_status SET DEFAULT 'In processing'";
        $this->execute($sql_document_status);

    }


    public function safeDown()
    {
        $this->dropTable('documents');
    }

}
