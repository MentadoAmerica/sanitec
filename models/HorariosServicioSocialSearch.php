<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\HorariosServicioSocial;

/**
 * HorariosServicioSocialSearch represents the model behind the search form of `app\models\HorariosServicioSocial`.
 */
class HorariosServicioSocialSearch extends HorariosServicioSocial
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_horario_ss', 'estado'], 'integer'],
            [['nombre_turno', 'dia_semana', 'hora_inicio', 'hora_fin'], 'safe'],
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
        $query = HorariosServicioSocial::find();

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
            'id_horario_ss' => $this->id_horario_ss,
            'hora_inicio' => $this->hora_inicio,
            'hora_fin' => $this->hora_fin,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'nombre_turno', $this->nombre_turno])
            ->andFilterWhere(['like', 'dia_semana', $this->dia_semana]);

        return $dataProvider;
    }
}
