<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Reportes;

/**
 * ReportesSearch represents the model behind the search form of `app\models\Reportes`.
 */
class ReportesSearch extends Reportes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_reporte', 'id_sanitario', 'id_tipo_reporte', 'id_verificacion', 'id_usuario_reporta'], 'integer'],
            [['fecha_hora_reporte', 'descripcion', 'evidencia_foto', 'prioridad', 'estado', 'fecha_cierre'], 'safe'],
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
        $query = Reportes::find();

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
            'id_reporte' => $this->id_reporte,
            'id_sanitario' => $this->id_sanitario,
            'id_tipo_reporte' => $this->id_tipo_reporte,
            'id_verificacion' => $this->id_verificacion,
            'id_usuario_reporta' => $this->id_usuario_reporta,
            'fecha_hora_reporte' => $this->fecha_hora_reporte,
            'fecha_cierre' => $this->fecha_cierre,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'evidencia_foto', $this->evidencia_foto])
            ->andFilterWhere(['like', 'prioridad', $this->prioridad])
            ->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }
}
