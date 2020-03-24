<?php

namespace app\models;

use yii\db\Query;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Objetos;

/**
 * ObjetosSearch represents the model behind the search form of `app\models\Objetos`.
 */
class ObjetosSearch extends Objetos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'productora_id', 'tipo_id', 'pais_id', 'capitulos'], 'integer'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Objetos::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('1=0');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'productora_id' => $this->productora_id,
            'tipo_id' => $this->tipo_id,
            'pais_id' => $this->pais_id,
            'fecha' => $this->fecha,
        ]);

        $query->andFilterWhere(['ilike', 'nombre', $this->nombre])
            ->andFilterWhere(['ilike', 'sinopsis', $this->sinopsis]);
        $query->andFilterHaving(['COUNT(c.id)' => $this->capitulos]);

        return $dataProvider;
    }

    public function getObjetos($id, $params)
    {
        $this->load($params);
        return (new Query())->from('objetos')->orderBy('id')
        ->andFilterWhere([
            'id' => $this->id,
            'productora_id' => $this->productora_id,
            'tipo_id' => $id,
            'pais_id' => $this->pais_id,
            'fecha' => $this->fecha,
        ])
        ->andFilterWhere(['ilike', 'nombre', $this->nombre])
        ->andFilterWhere(['ilike', 'sinopsis', $this->sinopsis])
        ->all();
    }

    public function getOneObject($id, $params)
    {
        $this->load($params);
        return (new Query())->from('objetos')->orderBy('id')
        ->andFilterWhere([
            'id' => $this->id,
            'productora_id' => $this->productora_id,
            'tipo_id' => $id,
            'pais_id' => $this->pais_id,
            'fecha' => $this->fecha,
        ])
        ->andFilterWhere(['ilike', 'nombre', $this->nombre])
        ->andFilterWhere(['ilike', 'sinopsis', $this->sinopsis])
        ->one();
    }
}
