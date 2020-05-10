<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notificacionesshows".
 *
 * @property int $id
 * @property int $user_id
 * @property int $show_id
 * @property string $mensaje
 *
 * @property Libros $show
 * @property Usuarios $user
 */
class Notificacionesshows extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notificacionesshows';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'show_id', 'mensaje'], 'required'],
            [['user_id', 'show_id'], 'default', 'value' => null],
            [['user_id', 'show_id'], 'integer'],
            [['mensaje'], 'string'],
            [['show_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shows::className(), 'targetAttribute' => ['show_id' => 'id']],
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
            'show_id' => 'Show ID',
            'mensaje' => 'Mensaje',
        ];
    }

    /**
     * Gets query for [[Show]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShow()
    {
        return $this->hasOne(Shows::className(), ['id' => 'show_id'])->inverseOf('notificacionesshows');
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'user_id'])->inverseOf('notificacionesshows');
    }
}
