<?php

namespace app\controllers;

use Yii;
use app\models\Transmision;
use app\models\TransmisionSearch;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TransmisionController implements the CRUD actions for Transmision model.
 */
class TransmisionController extends Controller
{
    public $layout = 'adminLTE3/main';
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Transmision models.
     * @return mixed
     */
    public function actionIndex()
    {
        if ( !\Yii::$app->user->can('transmision-listar')) {
            throw new ForbiddenHttpException();
        }
        
        $searchModel = new TransmisionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Transmision model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if ( !\Yii::$app->user->can('transmision-ver')) {
            throw new ForbiddenHttpException();
        }
        
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Transmision model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if ( !\Yii::$app->user->can('transmision-crear')) {
            throw new ForbiddenHttpException();
        }
        
        $model = new Transmision();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash("success","Registro $model->titulo creado satisfactoriamente!");
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
     * Updates an existing Transmision model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if ( !\Yii::$app->user->can('transmision-actualizar')) {
            throw new ForbiddenHttpException();
        }
        
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
        
            if ($model->save()) {
                Yii::$app->session->setFlash("success","Registro $model->titulo actualizado satisfactoriamente!");
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
     * Deletes an existing Transmision model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ( !\Yii::$app->user->can('transmision-borrar')) {
            throw new ForbiddenHttpException();
        }
        
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Transmision model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transmision the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transmision::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
