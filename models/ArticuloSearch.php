<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Articulo;
use Yii;

/**
 * ArticuloSearch represents the model behind the search form of `app\models\Articulo`.
 */
class ArticuloSearch extends Articulo
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'id', 'numero', 'categoria_id', 'estado', 'vistas', 
                    'descargas', 'curso_id'], 'integer'
                ],
            [
                [
                    'titulo', 'slug', 'tema', 'detalle', 'resumen', 'video', 
                    'descarga', 'etiquetas', 'usuario_crea', 'fecha_crea', 
                    'usuario_modifica', 'fecha_modifica'
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
    public function search($params, $categoria_id = null, $desde = null, $hasta = null)
    {
        $query = Articulo::find();

        // add conditions that should always apply here
        
        $query->joinWith('categoria');
        $query->joinWith('curso');
        
        $query->joinWith(['usuarioCrea as creado_por' => function ($q) {
            $q->andFilterWhere(['=', 'creado_por.username', $this->usuarioCrea]);
        }]);
        
        $query->joinWith(['usuarioModifica as actualizado_por' => function ($q) {
            $q->andFilterWhere(['=', 'actualizado_por.username', $this->usuarioModifica]);
        }]);

        if (!is_null($categoria_id)) {
            $query->andFilterWhere(['=', 'categoria_id', $categoria_id]);
        }

        if (!is_null($desde) and !is_null($hasta)) {
            $query->andFilterWhere(['>=', 'articulo.fecha_crea', $desde . ' 00:00:00']);
            $query->andFilterWhere(['<=', 'articulo.fecha_crea', $hasta . ' 23:59:59']);
        }

        // users no admin only can list their own registers
        if ( !Yii::$app->user->can('articulo-admin') ) {
            $query->andWhere('articulo.usuario_crea = ' . Yii::$app->user->identity->id);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder'      => [
                    'fecha_crea'    => SORT_DESC,
                    'titulo'        => SORT_ASC,
                ],
                'attributes'    => [
                    'titulo',
                    'fecha_crea',
                    'usuario_crea'   => [
                        'asc'   => ['usuarioCrea.name' => SORT_ASC],
                        'desc'   => ['usuarioCrea.name' => SORT_DESC],
                    ],
                    'vistas',
                    'descargas',
                    'cantidadComentarios',
                    'categoria_id'   => [
                        'asc'   => ['categoria' => SORT_ASC],
                        'desc'   => ['categoria' => SORT_DESC],
                    ],
                    'curso_id'   => [
                        'asc'   => ['curso.curso' => SORT_ASC],
                        'desc'   => ['curso.curso' => SORT_DESC],
                    ]
                ]
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
            'numero' => $this->numero,
            'categoria_id' => $this->categoria_id,
            'estado' => $this->estado,
            'vistas' => $this->vistas,
            'descargas' => $this->descargas,
            'curso_id' => $this->curso_id,
//            'usuario_crea' => $this->usuario_crea,
            'fecha_crea' => $this->fecha_crea,
//            'usuario_modifica' => $this->usuario_modifica,
            'fecha_modifica' => $this->fecha_modifica,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'tema', $this->tema])
            ->andFilterWhere(['like', 'detalle', $this->detalle])
            ->andFilterWhere(['like', 'resumen', $this->resumen])
            ->andFilterWhere(['like', 'video', $this->video])
            ->andFilterWhere(['like', 'descarga', $this->descarga])
            ->andFilterWhere(['like', 'etiquetas', $this->etiquetas]);
        
        $query->andFilterWhere(['like', 'creado_por.name', $this->usuario_crea])
               ->andFilterWhere(['like', 'actualizado_por.name', $this->usuario_modifica]);

        return $dataProvider;
    }
}
