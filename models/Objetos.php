<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "objetos".
 *
 * @property int $id
 * @property string $nombre
 * @property int $isbn
 * @property int $productora_id
 * @property int $tipo_id
 * @property int $pais_id
 * @property string|null $fecha
 * @property string|null $sinopsis
 *
 * @property Listacapitulos[] $listacapitulos
 * @property Listageneros[] $listageneros
 * @property Empresas $productora
 * @property Paises $pais
 * @property Tipoobjeto $tipo
 * @property Reparto[] $repartos
 * @property Usuarioseguimiento[] $usuarioseguimientos
 * @property Valoraciones[] $valoraciones
 */
class Objetos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'objetos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['fecha'], 'safe'],
            [['sinopsis'], 'string'],
            [['nombre'], 'string', 'max' => 255],
            [['nombre'], 'unique'],
            [['productora_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empresas::className(), 'targetAttribute' => ['productora_id' => 'id']],
            [['pais_id'], 'exist', 'skipOnError' => true, 'targetClass' => Paises::className(), 'targetAttribute' => ['pais_id' => 'id']],
            [['tipo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tipoobjeto::className(), 'targetAttribute' => ['tipo_id' => 'id']],
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
            'isbn' => 'Isbn',
            'productora_id' => 'Productora ID',
            'tipo_id' => 'Tipo ID',
            'pais_id' => 'Pais ID',
            'fecha' => 'Fecha',
            'sinopsis' => 'Sinopsis',
        ];
    }

    /**
     * Gets query for [[Listacapitulos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getListacapitulos()
    {
        return $this->hasMany(Listacapitulos::className(), ['objetos_id' => 'id'])->inverseOf('objetos');
    }

    /**
     * Gets query for [[Listageneros]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getListageneros()
    {
        return $this->hasMany(Listageneros::className(), ['objetos_id' => 'id'])->inverseOf('objetos');
    }

    /**
     * Gets query for [[Productora]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductora()
    {
        return $this->hasOne(Empresas::className(), ['id' => 'productora_id'])->inverseOf('objetos');
    }

    /**
     * Gets query for [[Pais]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPais()
    {
        return $this->hasOne(Paises::className(), ['id' => 'pais_id'])->inverseOf('objetos');
    }

    /**
     * Gets query for [[Tipo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipo()
    {
        return $this->hasOne(Tipoobjeto::className(), ['id' => 'tipo_id'])->inverseOf('objetos');
    }

    /**
     * Gets query for [[Repartos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRepartos()
    {
        return $this->hasMany(Reparto::className(), ['objetos_id' => 'id'])->inverseOf('objetos');
    }

    /**
     * Gets query for [[Usuarioseguimientos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioseguimientos()
    {
        return $this->hasMany(Usuarioseguimiento::className(), ['objetos_id' => 'id'])->inverseOf('objetos');
    }

    /**
     * Gets query for [[Valoraciones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getValoraciones()
    {
        return $this->hasMany(Valoraciones::className(), ['objetos_id' => 'id'])->inverseOf('objetos');
    }
}
