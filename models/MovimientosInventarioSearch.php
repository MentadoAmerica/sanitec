<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MovimientosInventario;

/**
 * MovimientosInventarioSearch represents the model behind the search form of `app\models\MovimientosInventario`.
 */
class MovimientosInventarioSearch extends MovimientosInventario
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_movimiento', 'id_material', 'id_usuario_registra', 'id_personal_receptor', 'id_edificio', 'id_sanitario'], 'integer'],
            [['tipo_movimiento', 'fecha_hora', 'proveedor', 'folio_documento', 'observaciones'], 'safe'],
            [['cantidad', 'costo_unitario', 'costo_total'], 'number'],
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
        $query = MovimientosInventario::find();

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
            'id_movimiento' => $this->id_movimiento,
            'id_material' => $this->id_material,
            'id_usuario_registra' => $this->id_usuario_registra,
            'id_personal_receptor' => $this->id_personal_receptor,
            'id_edificio' => $this->id_edificio,
            'id_sanitario' => $this->id_sanitario,
            'cantidad' => $this->cantidad,
            'costo_unitario' => $this->costo_unitario,
            'costo_total' => $this->costo_total,
            'fecha_hora' => $this->fecha_hora,
        ]);

        $query->andFilterWhere(['like', 'tipo_movimiento', $this->tipo_movimiento])
            ->andFilterWhere(['like', 'proveedor', $this->proveedor])
            ->andFilterWhere(['like', 'folio_documento', $this->folio_documento])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones]);

        return $dataProvider;
    }
}
