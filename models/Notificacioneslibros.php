<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notificacioneslibros".
 *
 * @property int $id
 * @property int $user_id
 * @property int $libro_id
 * @property string $mensaje
 *
 * @property Libros $libro
 * @property Usuarios $user
 */
class Notificacioneslibros extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notificacioneslibros';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'libro_id', 'mensaje'], 'required'],
            [['user_id', 'libro_id'], 'default', 'value' => null],
            [['user_id', 'libro_id'], 'integer'],
            [['mensaje'], 'string'],
            [['libro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Libros::className(), 'targetAttribute' => ['libro_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'libro_id' => 'Libro ID',
            'mensaje' => 'Mensaje',
        ];
    }

    /**
     * Gets query for [[Libro]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLibro()
    {
        return $this->hasOne(Libros::className(), ['id' => 'libro_id'])->inverseOf('notificacioneslibros');
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'user_id'])->inverseOf('notificacioneslibros');
    }
}
