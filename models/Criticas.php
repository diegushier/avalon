<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "criticas".
 *
 * @property int $id
 * @property int $libro_id
 * @property int $user_id
 * @property int $valoracion
 * @property string|null $comentario
 *
 * @property Libros $libro
 * @property Usuarios $user
 */
class Criticas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'criticas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['libro_id', 'usuario_id', 'valoracion'], 'required'],
            [['libro_id', 'usuario_id', 'valoracion'], 'default', 'value' => null],
            [['libro_id', 'usuario_id', 'valoracion'], 'integer'],
            [['comentario'], 'string'],
            [['libro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Libros::className(), 'targetAttribute' => ['libro_id' => 'id']],
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
            'usuario_id' => 'usuario ID',
            'valoracion' => 'Valoracion',
            'comentario' => 'Comentario',
        ];
    }

    /**
     * Gets query for [[Libro]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLibro()
    {
        return $this->hasOne(Libros::className(), ['id' => 'libro_id'])->inverseOf('criticas');
    }

    /**
     * Gets query for [[usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id'])->inverseOf('criticas');
    }
}
