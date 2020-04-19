<?php

namespace app\models;

use Yii;

/**
 * Creación deun capítulo y asignación de este a un Objeto Show de tipo 'serie'.
 */
class CapitulosForm extends \yii\db\ActiveRecord
{
    public $objetos_id;
    public $capitulo_id;
    public $nombre;
    public $sinopsis;
    public $temporada;

    public function rules()
    {
        return [
            [['nombre', 'sinopsis'], 'required'],
            [['sinopsis'], 'string'],
            [['nombre'], 'string', 'max' => 255],
            [['capitulo_id', 'objetos_id'], 'required'],
            [['capitulo_id', 'objetos_id'], 'default', 'value' => null],
            [['capitulo_id', 'objetos_id', 'temporada'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'nombre' => 'Nombre',
            'sinopsis' => 'Sinopsis',
            'capitulo_id' => 'Capitulo ID',
            'objetos_id' => 'Objetos ID',
            'temporada' => 'Temporada',
        ];
    }

    /**
     * Generación de un capítulo de una serie.
     *
     * @param [POST] $params
     * @return boolean
     */
    public function create($params)
    {
        $capitulo = new Capitulos();
        $capitulo->nombre = $params->nombre;
        $capitulo->sinopsis = $params->sinopsis;
        if ($capitulo->save()) {
            $capitulo = Capitulos::findPorNombre($capitulo->nombre);
            $lista = new Listacapitulos();
            $lista->objetos_id = $params->objetos_id;
            $lista->capitulo_id = $capitulo->id;
            $lista->save();
            return true;
        }

        return false;
    }
}
