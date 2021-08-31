<?php

namespace app\controllers;

use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use Yii;

/**
 * GraphicsController implements graphics
 */
class ApiController extends Controller {

    public $enableCsrfValidation = false;
    
    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        if ($action->id == 'users') {
            Yii::$app->request->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

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
                        'actions' => ['articulos', 'categorias', 'users'],
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'articulos' => ['GET'],
                    'categorias' => ['GET'],
                    'users' => ['GET'],
                ],
            ],
        ];
    }

    /**
     * Listar todos los artículos
     * @return JSon
     */
    public function actionArticulos()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $query = new Query;

        $query->select([
                'numero','titulo', 'articulo.slug', 'tema', 'detalle', 'resumen', 
                'video', 'etiquetas', 'vistas', 'descargas', 'curso', 'categoria', 
                'articulo.fecha_crea'
            ])
            ->join('left join', 'categoria', 'articulo.categoria_id = categoria.id')
            ->join('left join', 'curso', 'articulo.curso_id = curso.id')
            ->from('articulo');
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out['articulos'] = array_values($data);

        return $out;
    }
    
    /**
     * Listar todas las categorias
     * @return JSon
     */
    public function actionCategorias()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $query = new Query;

        $query->select([
                'categoria','slug', 'descripcion', 'imagen'
            ])
            ->from('categoria');
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out['categorias'] = array_values($data);

        return $out;
    }
    
    /**
     * Listar todos los usuarios
     * @return JSon
     */
    public function actionUsers()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $query = new Query;

        $query->select([
                'name','username', 'email', 'photo'
            ])
            ->from('user');
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out['users'] = array_values($data);

        return $out;
    }

}
