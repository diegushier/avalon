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
 * @property string|null $evento_id
 * @property string|null $fecha
 * @property string|null $sinopsis
 *
 * @property Listacapitulos[] $listacapitulos
 * @property Listageneros[] $listageneros
 * @property Reparto[] $repartos
 * @property Empresas $productora
 * @property Paises $pais
 * @property Usuarioshows[] $usuarioshows
 * @property Valoraciones[] $valoraciones
 */
class Shows extends \yii\db\ActiveRecord
{
    const PELICULAS = 'cine';
    const SERIES = 'serie';

    public $total;

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
            'evento_id' => 'Evento',
            'fecha' => 'Fecha',
            'sinopsis' => 'Sinopsis',
        ];
    }

    /*
     * Obtiene una consulta de los capitulos asociados al modelo
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getListacapitulos()
    {
        return $this->hasMany(Listacapitulos::className(), ['objetos_id' => 'id'])->inverseOf('objetos');
    }

    /*
     * Obtiene una consulta de los generos por id asociados al modelo
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getListageneros()
    {
        return $this->hasMany(Listageneros::className(), ['objetos_id' => 'id'])->inverseOf('objetos');
    }

    /*
     * Obtiene una consulta de los participantes asociados al modelo
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getRepartos()
    {
        return $this->hasMany(Reparto::className(), ['objetos_id' => 'id'])->inverseOf('objetos');
    }

    /*
     * Obtiene una consulta de la empresa asociada al modelo
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getProductora()
    {
        return $this->hasOne(Empresas::className(), ['id' => 'productora_id'])->inverseOf('shows');
    }

    /*
     * Obtiene una consulta de los paises asociados al modelo
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getPais()
    {
        return $this->hasOne(Paises::className(), ['id' => 'pais_id'])->inverseOf('shows');
    }

    /*
     * Obtiene una consulta de la asociación de usuarios y shows asociados al modelo
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getUsuarioshows()
    {
        return $this->hasMany(Usuarioshows::className(), ['objetos_id' => 'id'])->inverseOf('objetos');
    }

    /*
     * Obtiene una consulta de los generos asociados al modelo
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getGeneros()
    {
        return $this->hasMany(Generos::className(), ['id' => 'genero_id'])->via('listageneros');
    }

    /*
     * Obtiene una consulta de los capitulos asociados al modelo
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getCapitulos()
    {
        return $this->hasMany(Capitulos::className(), ['id' => 'capitulo_id'])->via('listacapitulos');
    }

    /*
     * Obtiene una consulta de las valoraciones asociadas al modelo
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getValoraciones()
    {
        return $this->hasMany(Valoraciones::className(), ['objetos_id' => 'id'])->inverseOf('objetos');
    }

    /*
     * Obtiene una consulta de las valoraciones con usuarios asociadas al modelo
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getCriticasWithUsers($sort = null)
    {
        return $this->getValoraciones()->joinWith('usuario u')->orderBy(isset($sort) ? $sort->orders : 'id')->all();
    }

    /*
     * Obtiene una consulta de la media de valoraciones asociadas al modelo
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getMedia($show_id)
    {
        return $this->getValoraciones()->select('COUNT (valoracion) AS suma, SUM (valoracion) AS total')->where('objetos_id = ' . $show_id)->one();
    }

    /*
     * Obtiene una consulta de las notificaciones asociadas al modelo
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getNotificacionesshows()
    {
        return $this->hasOne(Notificacionesshows::className(), ['show_id' => 'id'])->inverseOf('shows');
    }
}
