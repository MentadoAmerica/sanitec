<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Personal;

/**
 * PersonalSearch represents the model behind the search form of `app\models\Personal`.
 */
class PersonalSearch extends Personal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_personal', 'id_usuario', 'estado'], 'integer'],
            [['tipo_personal', 'identificador'], 'safe'],
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
        $query = Personal::find();

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
            'id_personal' => $this->id_personal,
            'id_usuario' => $this->id_usuario,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'tipo_personal', $this->tipo_personal])
            ->andFilterWhere(['like', 'identificador', $this->identificador]);

        return $dataProvider;
    }
}
