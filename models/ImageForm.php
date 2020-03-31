<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ImageForm extends Model
{
    public $imagen;

    public function rules()
    {
        return [
            [['imagen'], 'image', 'skipOnEmpty' => false, 'extensions' => ['png', 'jpg', 'jpeg']],
        ];
    }

    public function upload($id)
    {
        if ($this->validate()) {
            $filename = $id;
            $origen = Yii::getAlias('@imgCine/' . $filename . '.' . $this->imagen->extension);
            $destino = Yii::getAlias('@imgCine/' . $filename . '.jpg');
            $this->imagen->saveAs($origen);
            \yii\imagine\Image::resize($origen, 400, null)->save($destino);
            unlink($origen);
            return true;
        } else {
            return false;
        }
    }
}