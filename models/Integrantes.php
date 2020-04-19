<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "integrantes".
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $biografia
 * @property string|null $fecha
 *
 * @property Libros[] $libros
 * @property Reparto[] $repartos
 */
class Integrantes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'integrantes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['biografia'], 'string'],
            [['fecha'], 'safe'],
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
            'biografia' => 'Biografia',
            'fecha' => 'Fecha',
        ];
    }

    /**
     * Gets query for [[Libros]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLibros()
    {
        return $this->hasMany(Libros::className(), ['autor_id' => 'id'])->inverseOf('autor');
    }

    /**
     * Gets query for [[Repartos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRepartos()
    {
        return $this->hasMany(Reparto::className(), ['integrante_id' => 'id'])->inverseOf('integrante');
    }

    /**
     * Obtiene una lista de de todos los integrantes.
     *
     * @return array
     */
    public static function lista()
    {
        return static::find()->select('nombre')->orderBy('nombre')->indexBy('id')->column();
    }
}
