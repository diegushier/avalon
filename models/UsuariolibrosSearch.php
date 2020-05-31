<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Usuariolibros;

/**
 * UsuariolibrosSearch represents the model behind the search form of `app\models\Usuariolibros`.
 */
class UsuariolibrosSearch extends Usuariolibros
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'libro_id', 'usuario_id', 'seguimiento_id'], 'integer'],
            [['tipo'], 'safe'],
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
    public function search($params = null)
    {
        $query = Usuariolibros::find()->joinWith('libro');

        if (isset($params)) {
            $this->load($params);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'libro_id' => $this->libro_id,
            'usuario_id' => $this->usuario_id,
            'seguimiento_id' => $this->seguimiento_id,
        ]);

        $query->andFilterWhere(['ilike', 'tipo', $this->tipo]);

        return $query->all();
    }

}
