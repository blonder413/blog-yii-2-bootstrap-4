<?php
use miloschuman\highcharts\Highcharts;
$this->title = "Admin | Blonder413";
$this->params['breadcrumbs'][] = 'Admin';
?>

<div class="row">
    <div class="col-sm-12">
        <?= Highcharts::widget([
            'options' => [
               'title' => ['text' => 'Vistas por artículo'],
               'xAxis' => [
                    'labels'  => [
                        'rotation'    => -45,
                    ],
                    'type'    => 'category',
                ],
               'yAxis' => [
                  'title' => ['text' => 'Cantidad de vistas']
               ],
               'series' => [
                   // tipos: column, spline, pie
                   [
                       'type' => 'column', 'name' => 'vistas', 'data' => $mas_visitados,
                        'dataLabels' => [
                            'enabled' => true
                        ],
                    ],
         //         ['name' => 'Jane', 'data' => [1, 0, 4]],
         //         ['name' => 'John', 'data' => [5, 7, 3]]
                ],
                'credits' => ['enabled' => false],
            ]
         ]); ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
            <?= $this->renderAjax('/comentario/_index',
                [
                    'searchModel'   => $searchModel,
                    'dataProvider'  => $dataProvider,
                    'pendientes'    => $pendientes,
                ], null
            ); ?>
    </div>
</div>