<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Libros;
use yii\data\Sort;
use yii\db\Query;

/**
 * LibrosSearch represents the model behind the search form of `app\models\Libros`.
 */
class LibrosSearch extends Libros
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'isbn', 'editorial_id', 'autor_id', 'genero_id', 'pais_id'], 'integer'],
            [['nombre', 'fecha', 'sinopsis'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Búsqueda de  un elemento según parámetros.
     *
     * @param [POST] $params
     * @param [string] $sort
     * @return void
     */
    public function getObjetos($params, $sort)
    {
        $this->load($params);
        $query = Libros::find();
        $query->orderBy($sort->orders);
        $query->andFilterWhere([
            'id' => $this->id,
            'editorial_id' => $this->editorial_id,
            'pais_id' => $this->pais_id,
            'fecha' => $this->fecha,
        ]);

        $query->andFilterWhere(['ilike', 'nombre', $this->nombre]);
        $query->andFilterWhere(['ilike', 'sinopsis', $this->sinopsis]);

        return $query->all();
    }

}
