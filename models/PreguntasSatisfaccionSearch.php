<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class PreguntasSatisfaccionSearch extends PreguntasSatisfaccion
{
    public function rules()
    {
        return [
            [['id_pregunta', 'estado'], 'integer'],
            [['pregunta', 'categoria'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params, $formName = null)
    {
        $query = PreguntasSatisfaccion::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id_pregunta' => SORT_ASC],
            ],
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_pregunta' => $this->id_pregunta,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'pregunta', $this->pregunta])
            ->andFilterWhere(['like', 'categoria', $this->categoria]);

        return $dataProvider;
    }
}
