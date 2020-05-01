<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "seguimiento".
 *
 * @property int $id
 * @property string $nombre
 *
 * @property Usuarioseguimiento[] $usuarioseguimientos
 */
class Seguimiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'seguimiento';
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
     * Gets query for [[Usuarioseguimientos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioseguimientos()
    {
        return $this->hasMany(Usuarioseguimiento::className(), ['seguimiento_id' => 'id'])->inverseOf('seguimiento');
    }

    /**
     * Obtiene una lista de todos los tipos de seguimiento de libros, series o películas.
     *
     * @return array
     */
    public static function lista()
    {
        return static::find()->select('nombre')->orderBy('nombre')->indexBy('id')->column();
    }
}
