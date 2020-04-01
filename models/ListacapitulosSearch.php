<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Listacapitulos;

/**
 * ListacapitulosSearch represents the model behind the search form of `app\models\Listacapitulos`.
 */
class ListacapitulosSearch extends Listacapitulos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'capitulo_id', 'objetos_id', 'temporada'], 'integer'],
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
        $query = Listacapitulos::find();

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
            'capitulo_id' => $this->capitulo_id,
            'objetos_id' => $this->objetos_id,
            'temporada' => $this->temporada,
        ]);

        return $dataProvider;
    }
}
