<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ImageForm extends Model
{
    public $imagen;
    public $alias = [
        'libro' => '@imgLibros/',
        'cine' => '@imgCine/',
        'user' => '@imgUser/',

    ];

    public function rules()
    {
        return [
            [['imagen'], 'image', 'skipOnEmpty' => true, 'extensions' => ['jpg', 'png']],
        ];
    }

    public function upload($id, $tipo)
    {
        if ($this->validate()) {
            $filename = $id . '.' . $this->imagen->extension;
            $origen = Yii::getAlias($this->alias[$tipo] . $filename);
            $destino = Yii::getAlias($this->alias[$tipo] . $filename);
            $this->imagen->saveAs($origen);
            rename($origen, $destino);
            return true;
        } else {
            return false;
        }
    }

    public function delete($id, $tipo = null)
    {
        $extensions = ['.jpg', '.png'];
        foreach ($extensions as $k) {
            $file = Yii::getAlias($this->alias[$tipo] . $id . $k);
            if (file_exists($file)) {
                unlink($file);
                return;
            }
        }
    }
}
