<?php

namespace app\models\image;

use yii\db\ActiveRecord;

/**
 *
 */
class image extends ActiveRecord
{

    public function rules()
    {

        return [[['name', 'created_at','source'], 'required']];
    }
    public static function tableName(){
        return 'images';
    }

}