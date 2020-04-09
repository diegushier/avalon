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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Libros::find();

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
            'isbn' => $this->isbn,
            'editorial_id' => $this->editorial_id,
            'autor_id' => $this->autor_id,
            'genero_id' => $this->genero_id,
            'pais_id' => $this->pais_id,
            'fecha' => $this->fecha,
        ]);

        $query->andFilterWhere(['ilike', 'nombre', $this->nombre])
            ->andFilterWhere(['ilike', 'sinopsis', $this->sinopsis]);

        return $dataProvider;
    }

    public function getObjetos($params)
    {
        $this->load($params);
        $query = Libros::find();
        $query->orderBy('id');
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
