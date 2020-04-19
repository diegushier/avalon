<?php

namespace app\models;

/**
 * Clase desarrollada para la creación y asignación de un género a un Objeto Show.
 */
class GenerosForm extends \yii\db\ActiveRecord
{
    public $nombre;
    public $objetos_id;
    public $genero_id;

    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 255],
            [['objetos_id', 'genero_id'], 'required'],
            [['objetos_id', 'genero_id'], 'default', 'value' => null],
            [['objetos_id', 'genero_id'], 'integer'],
            [['genero_id'], 'exist', 'skipOnError' => true, 'targetClass' => Generos::className(), 'targetAttribute' => ['genero_id' => 'id']],
            [['objetos_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shows::className(), 'targetAttribute' => ['objetos_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'nombre' => 'Nombre',
            'objetos_id' => 'Objetos ID',
            'genero_id' => 'Genero ID',
        ];
    }

    public function create($params)
    {
        $genero = new Generos();
        $genero->nombre = $params->nombre;
        if ($genero->save()) {
            $genero = Generos::findPorNombre($genero->nombre);
            $lista = new Listageneros();
            $lista->objetos_id = $params->objetos_id;
            $lista->genero_id = $genero->id;
            $lista->save();
            return true;
        }

        return false;
    }
}
