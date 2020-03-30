<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "empresas".
 *
 * @property int $id
 * @property string $nombre
 * @property int $pais_id
 * @property int|null $entidad_id
 *
 * @property Paises $pais
 * @property Usuarios $entidad
 * @property Objetos[] $objetos
 */
class Empresas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'empresas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'pais_id'], 'required'],
            [['pais_id', 'entidad_id'], 'default', 'value' => null],
            [['pais_id', 'entidad_id'], 'integer'],
            [['nombre'], 'string', 'max' => 255],
            [['entidad_id'], 'unique'],
            [['nombre'], 'unique'],
            [['pais_id'], 'exist', 'skipOnError' => true, 'targetClass' => Paises::className(), 'targetAttribute' => ['pais_id' => 'id']],
            [['entidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['entidad_id' => 'id']],
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
            'pais_id' => 'Pais',
            'entidad_id' => 'Entidad ID',
        ];
    }

    /**
     * Gets query for [[Pais]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPais()
    {
        return $this->hasOne(Paises::className(), ['id' => 'pais_id'])->inverseOf('empresas');
    }

    /**
     * Gets query for [[Entidad]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEntidad()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'entidad_id'])->inverseOf('empresas');
    }

    /**
     * Gets query for [[Objetos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShows()
    {
        return $this->hasMany(Shows::className(), ['productora_id' => 'id'])->inverseOf('productora');
    }

    public function getLibros()
    {
        return $this->hasMany(Libros::className(), ['editorial_id' => 'id'])->inverseOf('editorial');
    }
}
