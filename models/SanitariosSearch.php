<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sanitarios;

/**
 * SanitariosSearch represents the model behind the search form of `app\models\Sanitarios`.
 */
class SanitariosSearch extends Sanitarios
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_sanitario', 'id_edificio', 'estado'], 'integer'],
            [['nombre', 'tipo', 'ubicacion', 'codigo_qr'], 'safe'],
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Sanitarios::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_sanitario' => $this->id_sanitario,
            'id_edificio' => $this->id_edificio,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'tipo', $this->tipo])
            ->andFilterWhere(['like', 'ubicacion', $this->ubicacion])
            ->andFilterWhere(['like', 'codigo_qr', $this->codigo_qr]);

        return $dataProvider;
    }
}
