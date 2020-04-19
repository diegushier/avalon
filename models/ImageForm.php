<?php

namespace app\models;

use Yii;
use yii\base\Model;

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
            $origen = Yii::getAlias($this->alias[$tipo] . $filename);
            $destino = Yii::getAlias($this->alias[$tipo] . $filename);
            $this->imagen->saveAs($origen);
            rename($origen, $destino);
            return true;
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
