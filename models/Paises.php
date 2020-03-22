<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "paises".
 *
 * @property int $id
 * @property string $nombre
 *
 * @property Empresas[] $empresas
 * @property Objetos[] $objetos
 * @property Usuarios[] $usuarios
 */
class Paises extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'paises';
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
     * Gets query for [[Empresas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresas()
    {
        return $this->hasMany(Empresas::className(), ['pais_id' => 'id'])->inverseOf('pais');
    }

    /**
     * Gets query for [[Objetos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObjetos()
    {
        return $this->hasMany(Objetos::className(), ['pais_id' => 'id'])->inverseOf('pais');
    }

    /**
     * Gets query for [[Usuarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuarios::className(), ['pais_id' => 'id'])->inverseOf('pais');
    }
}
