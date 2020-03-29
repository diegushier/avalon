<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipoobjeto".
 *
 * @property int $id
 * @property string $nombre
 *
 * @property Generos[] $generos
 * @property Objetos[] $objetos
 */
class Tipoobjeto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipoobjeto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 255],
            [['nombre'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * Gets query for [[Generos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGeneros()
    {
        return $this->hasMany(Generos::className(), ['tipo_id' => 'id'])->inverseOf('tipo');
    }

    /**
     * Gets query for [[Objetos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObjetos()
    {
        return $this->hasMany(Objetos::className(), ['tipo_id' => 'id'])->inverseOf('tipo');
    }

    public static function lista()
    {
        return static::find()->select('nombre')->orderBy('nombre')->indexBy('id')->column();
    }
}
