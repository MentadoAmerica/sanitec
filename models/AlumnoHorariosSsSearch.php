<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AlumnoHorariosSs;

/**
 * AlumnoHorariosSsSearch represents the model behind the search form of `app\models\AlumnoHorariosSs`.
 */
class AlumnoHorariosSsSearch extends AlumnoHorariosSs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_alumno_horario', 'id_alumno', 'id_horario_ss', 'estado'], 'integer'],
            [['fecha_inicio', 'fecha_fin'], 'safe'],
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
        $query = AlumnoHorariosSs::find();

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
            'id_alumno_horario' => $this->id_alumno_horario,
            'id_alumno' => $this->id_alumno,
            'id_horario_ss' => $this->id_horario_ss,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'estado' => $this->estado,
        ]);

        return $dataProvider;
    }
}
