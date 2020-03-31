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
            [['imagen'], 'image', 'skipOnEmpty' => false, 'extensions' => [ 'jpg', 'jpeg', 'png']],
        ];
    }

    public function upload($id, $tipo)
    {
        if ($this->validate()) {
            $tipo === 'libro' ? $alias = '@imgLibros/' : $alias = '@imgCine/';

            $filename = $id;
            $origen = Yii::getAlias($alias . $filename . '.' . $this->imagen->extension);
            $destino = Yii::getAlias($alias . $filename . '.jpg');
            $this->imagen->saveAs($origen);
            \yii\imagine\Image::resize($origen, 400, null)->save($destino);
            unlink($origen);
            return true;
        } else {
            return false;
        }
    }
}
