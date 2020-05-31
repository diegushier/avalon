<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Usuarioshows;

/**
 * UsuarioshowsSearch represents the model behind the search form of `app\models\Usuarioshows`.
 */
class UsuarioshowsSearch extends Usuarioshows
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'objetos_id', 'usuario_id', 'seguimiento_id'], 'integer'],
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
        $query = Usuarioshows::find()->joinWith('objetos o');

        if (isset($params)) {
            $this->load($params);
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'objetos_id' => $this->objetos_id,
            'usuario_id' => $this->usuario_id,
            'seguimiento_id' => $this->seguimiento_id,
        ]);

        $query->andFilterWhere(['ilike', 'tipo', $this->tipo]);

        return $query->all();
    }
}
