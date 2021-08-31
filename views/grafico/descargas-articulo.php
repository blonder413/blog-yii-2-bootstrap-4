<?php
use app\models\Helper;
use miloschuman\highcharts\Highcharts;

foreach ($model as $key => $value) {
    //$name[] = Helper::myTruncate($value->title, 5);
    $datos[] = [$value->titulo, $value->descargas];
}
?>

<div class="row">
    <div class="col-sm-12 col-md-6">
        <?= Highcharts::widget([
            'options' => [
               'title' => ['text' => 'Descargas por artículo'],
               'xAxis' => [
                    'labels'  => [
                        'rotation'    => -45,
                    ],
                    'type'    => 'category',
                ],
               'yAxis' => [
                  'title' => ['text' => 'Cantidad de descargas']
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
               'title' => ['text' => 'Descargas por artículo'],
               'xAxis' => [
                    'labels'  => [
                        'rotation'    => -45,
                    ],
                    'type'    => 'category',
                ],
               'yAxis' => [
                  'title' => ['text' => 'Cantidad de descargas']
               ],
               'series' => [
                   // tipos: column, spline, pie
                   ['type' => 'column', 'name' => 'vistas', 'data' => $datos,
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
               'title' => ['text' => 'Descargas por artículo'],
         //      'xAxis' => [
         //         'categorias' => ['vistas', 'descargas']
         //      ],
               'yAxis' => [
                  'title' => ['text' => 'Cantidad de descargas']
               ],
               'series' => [
                   // tipos: column, spline, pie
                   [
                       'type' => 'pie', 
                       'name' => 'vistas', 
                       'data' => $datos,
                       'dataLabels'    => [
                        'enabled' => true,
//                            'distance'  => -5,
                            'format' => '<b>{point.name}</b><br>{point.y} ({point.percentage::.2f} %)'
                    ]   ,
                    ],
                ],
                 'credits' => ['enabled' => false],
            ]
         ]); ?>
</div>
