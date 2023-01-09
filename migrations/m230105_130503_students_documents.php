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
            'document_status' => 'ENUM("Send for revision",
            "The document is accepted",
            "The document was not accepted",
            "The article has been sent for Anti-Plagiarism. The verification will take up to 3 days.",
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
            'comment'=>$this->string(),
            'draft_status'=> 'ENUM("draft","clear")',
        ]);
        $sql_document_status = "ALTER TABLE documents ALTER document_status SET DEFAULT 'In processing'";
        $this->execute($sql_document_status);

    }


    public function safeDown()
    {
        $this->dropTable('documents');
    }

}
