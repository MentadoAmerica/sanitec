<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class EvaluacionesSatisfaccionSearch extends EvaluacionesSatisfaccion
{
    public function rules()
    {
        return [
            [['id_evaluacion', 'id_sanitario', 'calificacion_general'], 'integer'],
            [['fecha_hora', 'turno', 'comentario'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params, $formName = null)
    {
        $query = EvaluacionesSatisfaccion::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['fecha_hora' => SORT_DESC],
            ],
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_evaluacion' => $this->id_evaluacion,
            'id_sanitario' => $this->id_sanitario,
            'turno' => $this->turno,
            'calificacion_general' => $this->calificacion_general,
        ]);

        $query->andFilterWhere(['like', 'comentario', $this->comentario])
            ->andFilterWhere(['like', 'fecha_hora', $this->fecha_hora]);

        return $dataProvider;
    }
}
