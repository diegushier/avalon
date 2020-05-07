<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuariolibros".
 *
 * @property int $id
 * @property int $libro_id
 * @property int $usuario_id
 * @property int $seguimiento_id
 * @property string $tipo
 *
 * @property Libros $libro
 * @property Seguimiento $seguimiento
 * @property Usuarios $usuario
 */
class Usuariolibros extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuariolibros';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['libro_id', 'usuario_id', 'seguimiento_id', 'tipo'], 'required'],
            [['libro_id', 'usuario_id', 'seguimiento_id'], 'default', 'value' => null],
            [['libro_id', 'usuario_id', 'seguimiento_id'], 'integer'],
            [['tipo'], 'string', 'max' => 10],
            [['libro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Libros::className(), 'targetAttribute' => ['libro_id' => 'id']],
            [['seguimiento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Seguimiento::className(), 'targetAttribute' => ['seguimiento_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'libro_id' => 'Libro ID',
            'usuario_id' => 'Usuario ID',
            'seguimiento_id' => 'Seguimiento ID',
            'tipo' => 'Tipo',
        ];
    }

    /**
     * Gets query for [[Libro]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLibro()
    {
        return $this->hasOne(Libros::className(), ['id' => 'libro_id'])->inverseOf('usuariolibros');
    }

    /**
     * Gets query for [[Seguimiento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeguimiento()
    {
        return $this->hasOne(Seguimiento::className(), ['id' => 'seguimiento_id'])->inverseOf('usuariolibros');
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id'])->inverseOf('usuariolibros');
    }
}
