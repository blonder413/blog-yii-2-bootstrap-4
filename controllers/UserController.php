<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\db\Query;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        if ( !\Yii::$app->user->can('user-listar')) {
            throw new ForbiddenHttpException();
        }
        
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if ( !\Yii::$app->user->can('user-ver')) {
            throw new ForbiddenHttpException();
        }
        
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if ( !\Yii::$app->user->can('user-crear')) {
            throw new ForbiddenHttpException();
        }
        
        $model = new User();

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
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if ( !\Yii::$app->user->can('user-actualizar')) {
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
     * Usuarios para los select
     * @param String $q usuario a buscar
     * @return Array Listado de los usuarios
     */
    public function actionBuscar($q = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;

            $query->select(['name as text', 'id'])
                    ->from('user')
                    ->orFilterWhere(['like', 'name', $q])
                    ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }

        return $out;
    }
    
    /**
     * Actualiza el estado de un usuario
     * Si se actualiza correctamente se redirige al index
     * @param integer $id id del usuario
     * @return mixed
     * @throws NotFoundHttpException si el registro no puede ser encontrado
     */
    public function actionCambiarEstado($id)
    {
        if ( !\Yii::$app->user->can('user-cambiar-estado')) {
            throw new ForbiddenHttpException();
        }
        
        $model = $this->findModel($id);
        if ($model->status == $model::STATUS_INACTIVE) {
            $model->status = $model::STATUS_ACTIVE;
        } elseif ($model->status == $model::STATUS_ACTIVE) {
            $model->status = $model::STATUS_INACTIVE;
        }
        
        if ($model->save()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app', "Usuario $model->username actualizado satisfactoriamente!")
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
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ( !\Yii::$app->user->can('user-eliminar')) {
            throw new ForbiddenHttpException();
        }
        
        $model = $this->findModel($id);
        
        if ($model->status != $model::STATUS_DELETED) {
            $model->status = $model::STATUS_DELETED;
        }
        
        if ($model->save()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app', "Usuario $model->username eliminado satisfactoriamente!")
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

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
