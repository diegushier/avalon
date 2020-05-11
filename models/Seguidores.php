<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "seguidores".
 *
 * @property int $id
 * @property int $user_id
 * @property int $seguidor_id
 *
 * @property Usuarios $user
 * @property Usuarios $seguidor
 */
class Seguidores extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'seguidores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'seguidor_id'], 'required'],
            [['user_id', 'seguidor_id'], 'default', 'value' => null],
            [['user_id', 'seguidor_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['seguidor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['seguidor_id' => 'id']],
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
            'seguidor_id' => 'Seguidor ID',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'user_id'])->inverseOf('seguidores');
    }

    /**
     * Gets query for [[Seguidor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeguidor()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'seguidor_id'])->inverseOf('seguidores0');
    }
}
