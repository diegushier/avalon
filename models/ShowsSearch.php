<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Shows;
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Shows::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'productora_id' => $this->productora_id,
            'pais_id' => $this->pais_id,
            'fecha' => $this->fecha,
        ]);

        $query->andFilterWhere(['ilike', 'nombre', $this->nombre])
            ->andFilterWhere(['ilike', 'tipo', $this->tipo])
            ->andFilterWhere(['ilike', 'sinopsis', $this->sinopsis]);

        return $dataProvider;
    }

    public function getObjetos($tipo, $params)
    {
        $this->load($params);
        return (new Query())->from('shows')->orderBy('id')
        ->andFilterWhere([
            'id' => $this->id,
            'productora_id' => $this->productora_id,
            'tipo' => $tipo,
            'pais_id' => $this->pais_id,
            'fecha' => $this->fecha,
        ])
        ->andFilterWhere(['ilike', 'nombre', $this->nombre])
        ->andFilterWhere(['ilike', 'sinopsis', $this->sinopsis])
        ->all();
    }
}
