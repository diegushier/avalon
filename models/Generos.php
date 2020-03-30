<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "generos".
 *
 * @property int $id
 * @property string $nombre
 *
 * @property Libros[] $libros
 * @property Listageneros[] $listageneros
 */
class Generos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'generos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 255],
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
     * Gets query for [[Libros]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLibros()
    {
        return $this->hasMany(Libros::className(), ['genero_id' => 'id'])->inverseOf('genero');
    }

    /**
     * Gets query for [[Listageneros]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getListageneros()
    {
        return $this->hasMany(Listageneros::className(), ['genero_id' => 'id'])->inverseOf('genero');
    }

    public static function lista()
    {
        return static::find()->select('nombre')->orderBy('nombre')->indexBy('id')->column();
    }
}
