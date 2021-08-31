<?php

namespace app\controllers;

use app\models\Articulo;
use yii\filters\AccessControl;
use yii\db\Query;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

/**
 * GraphicsController implements graphics
 */
class GraficoController extends Controller {

    public $layout = 'adminLTE3/main';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'vistas-articulo', 'descargas-articulo'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if ( !\Yii::$app->user->can('grafico-listar')) {
            throw new ForbiddenHttpException();
        }
        
        return $this->render('index');
    }
    
    public function actionVistasArticulo()
    {   
        if ( !\Yii::$app->user->can('grafico-listar')) {
            throw new ForbiddenHttpException();
        }
        
        $model = Articulo::find()->select(['titulo', 'vistas'])
                ->orderBy('vistas desc')
                ->limit(10)->all();
        
        foreach ($model as $key => $value) {
            //$name[] = Helper::myTruncate($value->title, 5);
            $datos[] = [$value->titulo, $value->vistas];
        }

        return $this->render('vistas-articulo', [
            'datos' => $datos,
            'model' => $model,
        ]);
    }
    
    public function actionDescargasArticulo()
    {
        if ( !\Yii::$app->user->can('grafico-listar')) {
            throw new ForbiddenHttpException();
        }
        
        $model = Articulo::find()->select(['titulo', 'descargas'])
                ->orderBy('descargas desc')
                ->limit(10)->all();
        return $this->render('descargas-articulo', [
            'model' => $model,
        ]);
    }
}