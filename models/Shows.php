<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shows".
 *
 * @property int $id
 * @property string $nombre
 * @property int $productora_id
 * @property string $tipo
 * @property int $pais_id
 * @property string|null $fecha
 * @property string|null $sinopsis
 *
 * @property Listacapitulos[] $listacapitulos
 * @property Listageneros[] $listageneros
 * @property Reparto[] $repartos
 * @property Empresas $productora
 * @property Paises $pais
 * @property Usuarioseguimiento[] $usuarioseguimientos
 * @property Valoraciones[] $valoraciones
 */
class Shows extends \yii\db\ActiveRecord
{
    const PELICULAS = 'cine';
    const SERIES = 'serie';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shows';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'tipo'], 'required'],
            [['fecha'], 'safe'],
            [['sinopsis'], 'string'],
            [['nombre', 'evento_id'], 'string', 'max' => 255],
            [['tipo'], 'string', 'max' => 10],
            [['nombre'], 'unique'],
            [['productora_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empresas::className(), 'targetAttribute' => ['productora_id' => 'id']],
            [['pais_id'], 'exist', 'skipOnError' => true, 'targetClass' => Paises::className(), 'targetAttribute' => ['pais_id' => 'id']],
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
            'productora_id' => 'Productora',
            'tipo' => 'Tipo',
            'pais_id' => 'Pais',
            'fecha' => 'Fecha',
            'evento_id' => 'Evento',
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

    public function getCapitulos()
    {
        return $this->hasMany(Capitulos::className(), ['id' => 'capitulo_id'])->via('listacapitulos');
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

    public function getGeneros()
    {
        return $this->hasMany(Generos::className(), ['id' => 'genero_id'])->via('listageneros');
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
     * Gets query for [[Productora]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductora()
    {
        return $this->hasOne(Empresas::className(), ['id' => 'productora_id'])->inverseOf('shows');
    }

    /**
     * Gets query for [[Pais]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPais()
    {
        return $this->hasOne(Paises::className(), ['id' => 'pais_id'])->inverseOf('shows');
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

    public function getCriticasWithUsers()
    {
        return $this->getValoraciones()->select('u.*, *')->joinWith('usuario u')->all();
    }
}
