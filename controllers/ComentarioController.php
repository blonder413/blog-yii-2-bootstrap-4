<?php

namespace app\controllers;

use Yii;
use app\models\Comentario;
use app\models\ComentarioSearch;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * ComentarioController implements the CRUD actions for Comentario model.
 */
class ComentarioController extends Controller
{
    public $layout = 'adminLTE3/main';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
//                'only' => ['index', 'view', 'create', 'delete','update', 'approve'],
                'rules' => [
//                    [
//                        'allow' => true,
//                        'actions' => ['index'],
//                        'roles' => ['?'],
//                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'index', 'index-ajax', 'view', 'create', 'update', 
                            'delete', 'aprobar'
                        ],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Comentario models.
     * @return mixed
     */
    public function actionIndex()
    {
        if ( !\Yii::$app->user->can('comentario-listar')) {
            throw new ForbiddenHttpException();
        }
        
        $searchModel = new ComentarioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $pendientes = Comentario::find('status = 0')->count();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pendientes'    => $pendientes,
        ]);
    }
    
    /**
     * Lista todos los comentarios
     * @return mixed
     */
    public function actionIndexAjax()
    {
        if ( !\Yii::$app->user->can('comentario-listar')) {
            throw new ForbiddenHttpException();
        }
        
        $searchModel = new ComentarioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        $pendientes = Comentario::find('estado = 0')->count();
        $pendientes = 0;
        return $this->renderAjax('_index', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
            'pendientes'    => $pendientes,
        ]);
    }
    
    /**
     * cambia el estado a ACTIVO
     * @return mixed
     */
    public function actionAprobar($id)
    {
        if ( !\Yii::$app->user->can('comentario-cambiar-estado')) {
            throw new ForbiddenHttpException();
        }
        
        $comentario = Comentario::findOne($id);
        
        $comentario->estado = Comentario::ESTADO_ACTIVO;
        
        if ($comentario->save()) {
          Yii::$app->session->setFlash("success", "Comentario aprobado exitosamente!");
        } else {
            $errors = '';
            foreach ($comentario->getErrors() as $key => $value) {
                foreach ($value as $row => $field) {
                    $errors .= $field . "<br>";
                }
            }

            Yii::$app->session->setFlash("danger", $errors);
        }
        return $this->redirect(['index']);
    }

    /**
     * Displays a single Comentario model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if ( !\Yii::$app->user->can('comentario-ver')) {
            throw new ForbiddenHttpException();
        }
        
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Comentario model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if ( !\Yii::$app->user->can('comentario-crear')) {
            throw new ForbiddenHttpException();
        }
        
        $model = new Comentario();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                    Yii::$app->session->setFlash("success","Registro $model->id creado satisfactoriamente!");
            } else {
                $errors = '';
                foreach ($model->getErrors() as $key => $value) {
                    foreach ($value as $row => $field) {
                        $errors .= $field . "<br>";
                    }
                }
                Yii::$app->session->setFlash("danger", $errors);    
            }
            
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Comentario model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if ( !\Yii::$app->user->can('comentario-actualizar')) {
            throw new ForbiddenHttpException();
        }
        
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
        
            if ($model->save()) {
                Yii::$app->session->setFlash("success","Registro $model->id actualizado satisfactoriamente!");
            } else {
                $errors = '';
                foreach ($model->getErrors() as $key => $value) {
                    foreach ($value as $row => $field) {
                        $errors .= $field . "<br>";
                    }
                }
                Yii::$app->session->setFlash("danger", $errors);
            }
        
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Comentario model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ( !\Yii::$app->user->can('comentario-borrar')) {
            throw new ForbiddenHttpException();
        }
        
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Comentario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Comentario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comentario::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
