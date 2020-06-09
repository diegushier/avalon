<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\imagine\Image;
use Imagine\Image\Box;

/**
 * Clase generarda para la subida y asignación de imagenes a Objetos de tipo Libros, Shows o Usuarios.
 */
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

    /**
     * Carga y subida de una imagen.
     *
     * @param [int] $id
     * @param [string] $tipo
     * @return boolean
     */
    public function upload($id, $tipo)
    {
        if ($this->validate()) {
            $filename = $id . '.' . $this->imagen->extension;
            $this->imagen->saveAs($this->alias[$tipo] . $filename);
            Image::thumbnail($this->alias[$tipo] . $this->imagen, 325, 500)
                ->resize(new Box(325, 500))
                ->save('../web/img/libros/' . $id . '.' . $this->imagen->extension, ['quality' => 70]);
        } else {
            return false;
        }
    }

    /**
     * Eliminación de imagen en caso de que el Objeto asignado tambíen haya sido borrado.
     *
     * @param [int] $id
     * @param [string] $tipo
     * @return void
     */
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
