<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Shows;
use Imagine\Filter\Basic\Show;
use yii\data\Sort;
use yii\db\Query;

/**
 * ShowsSearch represents the model behind the search form of `app\models\Shows`.
 */
class ShowsSearch extends Shows
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'productora_id', 'pais_id'], 'integer'],
            [['nombre', 'tipo', 'fecha', 'sinopsis'], 'safe'],
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
     * Query generada mediante los parámetros requeridos.
     *
     * @param [string] $tipo
     * @param [POST] $params
     * @param [string] $sort
     * @return \yii\db\ActiveQuery
     */
    public function getObjetos($tipo, $params, $sort = null)
    {
        $this->load($params);
        $query = Shows::find();
        $query->andFilterWhere([
            'id' => $this->id,
            'productora_id' => $this->productora_id,
            'tipo' => $tipo,
            'pais_id' => $this->pais_id,
            'fecha' => $this->fecha,
        ]);
        $query->andFilterWhere(['ilike', 'nombre', $this->nombre]);
        $query->andFilterWhere(['ilike', 'sinopsis', $this->sinopsis]);
        $query->orderBy(isset($sort) ? $sort->orders : 'id');
        return $query->all();
    }
}
