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
            'personal_data'=>$this->boolean(),
            'user_id'=>$this->integer(),
            'fio'=>$this->string(),
            'title' => $this->string(),
            'authors' => $this->string(),
            'coauthor' => $this->string(),
            'nr' => $this->string(),
            'university' => $this->string(),
            'collection' => $this->string(),
            'source' => $this->string(),
            'datetime' => $this->string(),
            'originality' => $this->integer(),
            'organization' => $this->string(),
            'email' => $this->string(),
            'phone'=>$this->string(),
            'city' => $this->string(),
            'count_additional_document'=>$this->integer(),
            'document_status' => 'ENUM("The article did not pass the originality test",
 "The article has been checked for originality", 
"The article does not meet the requirements", 
"The article was rejected as an incomplete set of documents", 
"The article has been accepted for review", 
"The article has been accepted for publication",
"In processing",
"In the draft",
"Clear",
"Article under consideration")',
            'comment'=>$this->text(),
            'draft_status'=> 'ENUM("draft","clear")',
        ]);
        $sql_document_status = "ALTER TABLE documents ALTER document_status SET DEFAULT 'Article under consideration'";
        $personal_data = "ALTER TABLE documents ALTER personal_data SET DEFAULT 0";
        $this->execute($sql_document_status);
        $this->execute($personal_data);

    }


    public function safeDown()
    {
        $this->dropTable('documents');
    }

}
