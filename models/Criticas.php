<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "criticas".
 *
 * @property int $id
 * @property int $libro_id
 * @property int $usuario_id
 * @property int $valoracion
 * @property string|null $comentario
 * @property string $fecha
 *
 * @property Libros $libro
 * @property Usuarios $usuario
 */
class Criticas extends \yii\db\ActiveRecord
{
    public $total;
    public $suma;
    
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
            [['fecha'], 'safe'],
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
            'usuario_id' => 'Usuario ID',
            'valoracion' => 'Valoracion',
            'comentario' => 'Comentario',
            'fecha' => 'Fecha',
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
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id'])->inverseOf('criticas');
    }
}
