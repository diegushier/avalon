<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reparto".
 *
 * @property int $id
 * @property int $objetos_id
 * @property int $integrante_id
 * @property int $rol_id
 *
 * @property Integrantes $integrante
 * @property Roles $rol
 * @property Shows $objetos
 */
class Reparto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reparto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['integrante_id'], 'exist', 'skipOnError' => true, 'targetClass' => Integrantes::className(), 'targetAttribute' => ['integrante_id' => 'id']],
            [['rol_id'], 'exist', 'skipOnError' => true, 'targetClass' => Roles::className(), 'targetAttribute' => ['rol_id' => 'id']],
            [['objetos_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shows::className(), 'targetAttribute' => ['objetos_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'objetos_id' => 'Objetos ID',
            'integrante_id' => 'Integrante ID',
            'rol_id' => 'Rol ID',
        ];
    }

    /**
     * Gets query for [[Integrante]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIntegrante()
    {
        return $this->hasOne(Integrantes::className(), ['id' => 'integrante_id'])->inverseOf('repartos');
    }

    /**
     * Gets query for [[Rol]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRol()
    {
        return $this->hasOne(Roles::className(), ['id' => 'rol_id'])->inverseOf('repartos');
    }

    /**
     * Gets query for [[Objetos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObjetos()
    {
        return $this->hasOne(Shows::className(), ['id' => 'objetos_id'])->inverseOf('repartos');
    }
}
