<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Vuelos;

/**
 * VuelosSearch represents the model behind the search form of `app\models\Vuelos`.
 */
class VuelosSearch extends Vuelos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'origen_id', 'destino_id', 'compania_id'], 'integer'],
            [['codigo', 'salida', 'llegada', 'origen.codigo', 'destino.codigo', 'compania.denominacion'], 'safe'],
            [['plazas', 'precio', 'restantes'], 'number'],
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Vuelos::find2()->where('salida > localtimestamp');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['origen o', 'destino d', 'compania c'])
            ->addGroupBy('o.codigo, d.codigo, c.denominacion')
            ->having('plazas - COUNT(r.id) > 0');

        $dataProvider->sort->attributes['origen.codigo'] = [
            'asc' => ['o.codigo' => SORT_ASC],
            'desc' => ['o.codigo' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['destino.codigo'] = [
            'asc' => ['d.codigo' => SORT_ASC],
            'desc' => ['d.codigo' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['compania.denominacion'] = [
            'asc' => ['c.denominacion' => SORT_ASC],
            'desc' => ['c.denominacion' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['restantes'] = [
            'asc' => ['plazas - COUNT(r.id)' => SORT_ASC],
            'desc' => ['plazas - COUNT(r.id)' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'origen_id' => $this->origen_id,
            'destino_id' => $this->destino_id,
            'compania_id' => $this->compania_id,
            'salida' => $this->salida,
            'llegada' => $this->llegada,
            'plazas' => $this->plazas,
            'precio' => $this->precio,
        ]);

        $query->andFilterWhere(['ilike', 'codigo', $this->codigo])
            ->andFilterWhere(['ilike', 'o.codigo', $this->getAttribute('origen.codigo')])
            ->andFilterWhere(['ilike', 'd.codigo', $this->getAttribute('destino.denom')])
            ->andFilterWhere(['ilike', 'c.denominacion', $this->getAttribute('compania.denominacion')])
            ->andFilterHaving(['plazas - COUNT(r.id)' => $this->restantes]);
        return $dataProvider;
    }
}
