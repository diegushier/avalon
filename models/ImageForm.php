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
            [['imagen'], 'image', 'skipOnEmpty' => false, 'extensions' => ['jpg', 'png']],
        ];
    }

    public function upload($id, $tipo)
    {
        if ($this->validate()) {
            $tipo === 'libro' ? $alias = '@imgLibros/' : $alias = '@imgCine/';
            $filename = $id . '.' . $this->imagen->extension;
            $origen = Yii::getAlias($alias . $filename);
            $destino = Yii::getAlias($alias . $filename);
            $this->imagen->saveAs($origen);
            rename($origen, $destino);
            return true;
        } else {
            return false;
        }
    }

    public function delete($id, $tipo)
    {
        $tipo === 'libro' ? $alias = '@imgLibros/' : $alias = '@imgCine/';
        $file = Yii::getAlias($tipo . $id);
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
