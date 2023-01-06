<?php

namespace app\controllers;

use app\models\image\form\UploadForm;
use app\models\image\image;
use Yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\UploadedFile;

function transliterate($name)
{
    
}

class ImageController extends Controller
{
    public function actionList()
    {

        $list = image::find()->all();
        return $this->render('table',['list'=>$list]);
    }
    public function actionForm()
    {
        $model = new UploadForm();
        if (Yii::$app->request->post('UploadForm')) {
            $model->images = UploadedFile::getInstances($model, 'images');
            $result = $model->upload();
            if ($result==true){
                foreach ($model->images as $image){
                    $name = strtolower($image->baseName);
                    $name = transliterate($name);
                    $images = new image();
                    $images->name = $name;
                    $images->created_at = date('d-m-Y H-i-s');
                    $images->source = 'UploadDocument/' . $name . '.' . $image->extension;
                    $images->save();
                }
                Yii::$app->session->setFlash('success', 'Успешно');
            }else {
                Yii::$app->session->setFlash('error', 'Не удалось');
            }
        }
        return $this->render('form',['model'=>$model]);
    }

}