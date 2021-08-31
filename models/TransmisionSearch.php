<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Transmision;

/**
 * TransmisionSearch represents the model behind the search form of `app\models\Transmision`.
 */
class TransmisionSearch extends Transmision
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [
                ['titulo', 'descripcion', 'video', 'inicio', 'fin', 'usuario_crea', 
                    'fecha_crea', 'usuario_modifica', 'fecha_modifica'
                ], 
                'safe'
            ],
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
        $query = Transmision::find();

        // add conditions that should always apply here
        
        $query->joinWith(['usuarioCrea as creado_por' => function ($q) {
            $q->andFilterWhere(['=', 'creado_por.username', $this->usuarioCrea]);
        }]);
        
        $query->joinWith(['usuarioModifica as actualizado_por' => function ($q) {
            $q->andFilterWhere(['=', 'actualizado_por.username', $this->usuarioModifica]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder'  => [
                    'titulo'  => SORT_ASC,
                ],
                'attributes' => [
                    'titulo',
                    'descripcion',
                    'inicio',
                    'fin',
                    'usuario_crea'   => [
                        'asc'   => ['usuarioCrea.name' => SORT_ASC],
                        'desc'   => ['usuarioCrea.name' => SORT_DESC],
                    ],
                    'fecha_crea',
                    'usuario_modifica'   => [
                        'asc'   => ['usuarioModifica.name' => SORT_ASC],
                        'desc'   => ['usuarioModifica.name' => SORT_DESC],
                    ],
                    'fecha_modifica'
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
//            'inicio' => $this->inicio,
//            'fin' => $this->fin,
//            'usuario_crea' => $this->usuario_crea,
            'fecha_crea' => $this->fecha_crea,
//            'usuario_modifica' => $this->usuario_modifica,
            'fecha_modifica' => $this->fecha_modifica,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'video', $this->video])
            ->andFilterWhere(['like', 'inicio', $this->inicio])
            ->andFilterWhere(['like', 'fin', $this->fin]);
        
        $query->andFilterWhere(['like', 'creado_por.name', $this->usuario_crea])
               ->andFilterWhere(['like', 'actualizado_por.name', $this->usuario_modifica]);

        return $dataProvider;
    }
}
