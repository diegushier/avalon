<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "objetos".
 *
 * @property int $id
 * @property string $nombre
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

    const PELICULAS = 1;
    const SERIES = 2;
    const LIBROS = 3;

    private $_imagen = null;
    private $_imagenUrl = null;

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
            [['isbn'], 'unique'],
            [['isbn'], 'integer'],
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
            'productora_id' => 'Productora',
            'isbn' => 'ISBN',
            'tipo_id' => 'Tipo',
            'pais_id' => 'Pais',
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

    public function getUsuarios()
    {
        return $this->hasMany(Usuarios::class, ['id' => 'entidad_id'])->via('productora');
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

    public function getImagen()
    {
        if ($this->_imagen !== null) {
            return $this->_imagen;
        }

        $this->setImagen(Yii::getAlias('@img/' . $this->id . '.jpg'));
        return $this->_imagen;
    }

    public function setImagen($imagen)
    {
        $this->_imagen = $imagen;
    }

    public function getImagenUrl()
    {
        if ($this->_imagenUrl !== null) {
            return $this->_imagenUrl;
        }

        $this->setImagenUrl(Yii::getAlias('@imgUrl/' . $this->id . '.jpg'));
        return $this->_imagenUrl;
    }

    public function setImagenUrl($imagenUrl)
    {
        $this->_imagenUrl = $imagenUrl;
    }
}
