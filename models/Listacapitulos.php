<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "listacapitulos".
 *
 * @property int $id
 * @property int $capitulo_id
 * @property int $objetos_id
 * @property int $temporada
 *
 * @property Capitulos $capitulo
 * @property Shows $objetos
 */
class Listacapitulos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'listacapitulos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['capitulo_id', 'objetos_id'], 'required'],
            [['capitulo_id', 'objetos_id'], 'default', 'value' => null],
            [['capitulo_id', 'objetos_id'], 'integer'],
            [['capitulo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Capitulos::className(), 'targetAttribute' => ['capitulo_id' => 'id']],
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
            'capitulo_id' => 'Capitulo ID',
            'objetos_id' => 'Objetos ID',
            'temporada' => 'Temporada',
        ];
    }

    /**
     * Gets query for [[Capitulo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCapitulo()
    {
        return $this->hasOne(Capitulos::className(), ['id' => 'capitulo_id'])->inverseOf('listacapitulos');
    }

    /**
     * Gets query for [[Objetos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObjetos()
    {
        return $this->hasOne(Shows::className(), ['id' => 'objetos_id'])->inverseOf('listacapitulos');
    }
}
