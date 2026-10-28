<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\HistorialReportes;

/**
 * HistorialReportesSearch represents the model behind the search form of `app\models\HistorialReportes`.
 */
class HistorialReportesSearch extends HistorialReportes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_historial', 'id_reporte', 'id_usuario'], 'integer'],
            [['estado_anterior', 'estado_nuevo', 'fecha_hora', 'observaciones'], 'safe'],
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
        $query = HistorialReportes::find();

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
            'id_historial' => $this->id_historial,
            'id_reporte' => $this->id_reporte,
            'id_usuario' => $this->id_usuario,
            'fecha_hora' => $this->fecha_hora,
        ]);

        $query->andFilterWhere(['like', 'estado_anterior', $this->estado_anterior])
            ->andFilterWhere(['like', 'estado_nuevo', $this->estado_nuevo])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones]);

        return $dataProvider;
    }
}
