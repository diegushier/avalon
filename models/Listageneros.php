<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "listageneros".
 *
 * @property int $id
 * @property int $objetos_id
 * @property int $genero_id
 *
 * @property Generos $genero
 * @property Shows $objetos
 */
class Listageneros extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'listageneros';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['objetos_id', 'genero_id'], 'required'],
            [['objetos_id', 'genero_id'], 'default', 'value' => null],
            [['objetos_id', 'genero_id'], 'integer'],
            [['genero_id'], 'exist', 'skipOnError' => true, 'targetClass' => Generos::className(), 'targetAttribute' => ['genero_id' => 'id']],
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
            'genero_id' => 'Genero ID',
        ];
    }

    /**
     * Gets query for [[Genero]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGenero()
    {
        return $this->hasOne(Generos::className(), ['id' => 'genero_id'])->inverseOf('listageneros');
    }

    /**
     * Gets query for [[Objetos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObjetos()
    {
        return $this->hasOne(Shows::className(), ['id' => 'objetos_id'])->inverseOf('listageneros');
    }
}
