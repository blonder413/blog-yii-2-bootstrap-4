<?php
use app\models\Helper;
use miloschuman\highcharts\Highcharts;

$this->title = "Vistas por artículo | Blonder413";
$this->params['breadcrumbs'][] = [
    'label' => 'gráficos', 
    'url' => ['grafico/index']
];
$this->params['breadcrumbs'][] = 'Vistas por artículo';
?>

<div class="row">
    <div class="col-sm-12 col-md-6">
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
                   ['type' => 'spline', 'name' => 'vistas', 'data' => $datos,
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
    <div class="col-sm-12 col-md-6">
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
                       'type' => 'column', 'name' => 'vistas', 'data' => $datos,
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

<div class="col-sm-12">
        <?= Highcharts::widget([
            'options' => [
                
               'title' => ['text' => 'Vistas por artículo'],
         //      'xAxis' => [
         //         'categorias' => ['vistas', 'descargas']
         //      ],
               'yAxis' => [
                  'title' => ['text' => 'Cantidad de vistas']
               ],
               'series' => [
                   // tipos: column, spline, pie
                   [
                        'allowPointSelect'  => true,
                       'type' => 'pie', 'name' => 'vistas', 'data' => $datos,
                        'cursor'    => ['pointer'],
                        'dataLabels'    => [
                            'enabled' => true,
//                            'distance'  => -5,
                            'format' => '<b>{point.name}</b><br>{point.y} ({point.percentage::.2f} %)'
                        ],
                    ],
         //         ['name' => 'Jane', 'data' => [1, 0, 4]],
         //         ['name' => 'John', 'data' => [5, 7, 3]]
                 ],
                 'credits' => ['enabled' => false],
            ]
         ]); ?>
</div>