<?php

namespace app\controllers;

use Yii;
use app\models\Articulo;
use app\models\ArticuloSearch;
use yii\db\Exception;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ArticulosController implements the CRUD actions for Articulo model.
 */
class ArticulosController extends Controller
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
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'cambiar-estado', 'view', 'create', 'update', 'delete'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['buscar'],
                        'roles' => ['?', '@'],
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
     * Lists all Articulo models.
     * @return mixed
     */
    public function actionIndex($desde = null, $hasta = null)
    {
        if ( !\Yii::$app->user->can('articulo-listar')) {
            throw new ForbiddenHttpException();
        }
        /*
        ----------------------------------------------------
        BATCH
        ----------------------------------------------------
        $model = (new yii\db\Query)->select(['category_id'])->distinct('category_id')->from('articles');
        
        foreach ($model->each() as $key => $value) {
	    echo $value['genero'] . '<br>';
	}
        */
        
        $searchModel = new ArticuloSearch();

        if (is_null($desde) or is_null($hasta)) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, null, $desde, $hasta);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Articulo model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        if ( 
                !\Yii::$app->user->can('articulo-admin') and 
                !\Yii::$app->user->can('articulo-ver', ['model' => $model])
            ) {
            throw new ForbiddenHttpException();
        }
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Articulo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if ( !\Yii::$app->user->can('articulo-crear')) {
            throw new ForbiddenHttpException();
        }
        
        $model = new Articulo();
        $model->loadDefaultValues();
        if ($model->load(Yii::$app->request->post())) {
//            $transaction = Article::getDb()->beginTransaction();
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $model->detalle = html_entity_decode($model->detalle);
                if ($model->save()) {
                    Yii::$app->session->setFlash("success", "Artículo $model->titulo creado satisfactoriamente!");
                } else {
                    $errors = '';
                    foreach ($model->getErrors() as $key => $value) {
                        foreach ($value as $row => $field) {
                            $errors .= $field . "<br>";
                        }
                    }
                    Yii::$app->session->setFlash("danger", $errors);
                    return $this->render('create', [
                                'model' => $model,
                    ]);
                }
                $transaction->commit();
                return $this->redirect(['index']);
            } catch (\Exception $e) {           // PHP 5
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {            // PHP 7
                $transaction->rollBack();
                throw $e;
//          } catch(yii\db\StaleObjectException $e) {
//            Yii::$app->session->setFlash("danger", "El registro ha sido modificado por otro usuario");
//			throw $e;	// the register has been modified for multiples users
            }
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Articulo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ( 
                !\Yii::$app->user->can('articulo-admin') and 
                !\Yii::$app->user->can('articulo-borrar', ['model' => $model])
            ) {
            throw new ForbiddenHttpException();
        }

        if ($model->load(Yii::$app->request->post())) {

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->detalle = html_entity_decode($model->detalle);
                if ($model->update()) {
                    Yii::$app->session->setFlash("success", "Artículo $model->titulo actualizado satisfactoriamente!");
                } else {
                    $errors = '';
                    foreach ($model->getErrors() as $key => $value) {
                        foreach ($value as $row => $field) {
                            $errors .= $field . "<br>";
                        }
                    }
                    $transaction->rollBack();
                    Yii::$app->session->setFlash("danger", $errors);
                }
                $transaction->commit();
            } catch (Exception $e) {
                Yii::$app->session->setFlash("danger", "Artículo $model->titulo no puede ser actualizado!");
                return $this->redirect(['view', 'id' => $model->id]);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Articulo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if ( 
                !\Yii::$app->user->can('articulo-admin') and 
                !\Yii::$app->user->can('articulo-borrar', ['model' => $model])
            ) {
            throw new ForbiddenHttpException();
        }
        
        try {
            $model->delete();
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app', "Artículo $model->titulo eliminado satisfactoriamente")
            );
        } catch(Exception $e) {
            
            if ($e->errorInfo[1] == 1451) {
                Yii::$app->session->setFlash(
                    'warning',
                    Yii::t(
                        'app',
                        'No se puede borrar el artículo porque tiene registros asociados'
                    )
                );
            } else {
                Yii::$app->session->setFlash(
                    'error',
                    Yii::t(
                        'app',
                        'Error al eliminar el registro'
                    )
                );
            }
            return $this->redirect(['index']);
        }

        return $this->redirect(['index']);
    }
    
    /**
     * Actualiza el estado de un artículo
     * Si se actualiza correctamente se redirige al index
     * @param integer $id id del artículo
     * @return mixed
     * @throws NotFoundHttpException si el registro no puede ser encontrado
     */
    public function actionCambiarEstado($id)
    {
        if ( !\Yii::$app->user->can('articulo-cambiar-estado')) {
            throw new ForbiddenHttpException();
        }
        
        $model = $this->findModel($id);
        if ($model->estado == $model::ESTADO_INACTIVO) {
            $model->estado = $model::ESTADO_ACTIVO;
        } elseif ($model->estado == $model::ESTADO_ACTIVO) {
            $model->estado = $model::ESTADO_INACTIVO;
        }
        if ($model->save()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app', "Artículo $model->titulo actualizado satisfactoriamente!")
            );
        } else {
            $errors = '';
            foreach ($model->getErrors() as $key => $value) {
                foreach ($value as $row => $field) {
                    $errors .= $field . "<br>";
                }
            }

            Yii::$app->session->setFlash("danger", $errors);
        }
        
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Artículos para los select
     * @param String $q artículo a buscar
     * @return Array Listado de los artículos
     */
    public function actionBuscar($q = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;

            $query->select(['titulo as text', 'id'])
                    ->from('articulo')
                    ->orFilterWhere(['like', 'titulo', $q])
                    ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }

        return $out;
    }

    /**
     * Finds the Articulo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Articulo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Articulo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
