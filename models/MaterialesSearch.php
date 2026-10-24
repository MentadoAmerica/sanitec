<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Materiales;

/**
 * MaterialesSearch represents the model behind the search form of `app\models\Materiales`.
 */
class MaterialesSearch extends Materiales
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_material', 'estado'], 'integer'],
            [['nombre', 'descripcion', 'unidad_medida'], 'safe'],
            [['stock_actual', 'stock_minimo', 'costo_unitario'], 'number'],
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
        $query = Materiales::find();

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
            'id_material' => $this->id_material,
            'stock_actual' => $this->stock_actual,
            'stock_minimo' => $this->stock_minimo,
            'costo_unitario' => $this->costo_unitario,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'unidad_medida', $this->unidad_medida]);

        return $dataProvider;
    }
}
