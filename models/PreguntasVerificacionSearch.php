<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PreguntasVerificacion;

/**
 * PreguntasVerificacionSearch represents the model behind the search form of `app\models\PreguntasVerificacion`.
 */
class PreguntasVerificacionSearch extends PreguntasVerificacion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pregunta', 'orden', 'obligatoria', 'estado'], 'integer'],
            [['pregunta', 'categoria', 'tipo_respuesta', 'opciones_json', 'respuestas_reporte_json'], 'safe'],
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
        $query = PreguntasVerificacion::find();

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
            'id_pregunta' => $this->id_pregunta,
            'orden' => $this->orden,
            'obligatoria' => $this->obligatoria,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'pregunta', $this->pregunta])
            ->andFilterWhere(['like', 'categoria', $this->categoria])
            ->andFilterWhere(['like', 'tipo_respuesta', $this->tipo_respuesta])
            ->andFilterWhere(['like', 'opciones_json', $this->opciones_json])
            ->andFilterWhere(['like', 'respuestas_reporte_json', $this->respuestas_reporte_json]);

        return $dataProvider;
    }
}
