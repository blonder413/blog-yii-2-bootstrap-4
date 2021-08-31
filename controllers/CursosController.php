<?php

namespace app\controllers;

use Yii;
use app\models\Curso;
use app\models\CursoSearch;
use app\models\Helper;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * CursoController implements the CRUD actions for Curso model.
 */
class CursosController extends Controller
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
  //                'only' => ['index', 'view', 'create', 'delete','update'],
                'rules' => [
  //                    [
  //                        'allow' => true,
  //                        'actions' => ['index'],
  //                        'roles' => ['?'],
  //                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'view', 'update', 'delete'],
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
     * Lists all Curso models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        if ( !\Yii::$app->user->can('curso-listar')) {
            throw new ForbiddenHttpException();
        }
        
        $searchModel = new CursoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Curso model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if ( !\Yii::$app->user->can('curso-ver')) {
            throw new ForbiddenHttpException();
        }
        
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Curso model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if ( !\Yii::$app->user->can('curso-crear')) {
            throw new ForbiddenHttpException();
        }
        
        $model = new Curso(['scenario' => 'crear']);
        if ($model->load(Yii::$app->request->post())) {
          $model->archivo = UploadedFile::getInstance($model, 'archivo');
          $model->imagen = Helper::limpiaUrl($model->curso . '.' . $model->archivo->extension);
          if ($model->save()) {
            $model->archivo->saveAs( 'img/cursos/' . $model->imagen);
            Yii::$app->session->setFlash('success', Yii::t('app', "Courso $model->curso creado satisfactoriamente"));
            return $this->redirect(['index']);
          } else {
            $errors = '<ul>';
               foreach ($model->getErrors() as $key => $value) {
                   foreach ($value as $row => $field) {
                       //Yii::$app->session->setFlash("danger", $field);
                       $errors .= "<li>" . $field . "</li>";
                   }
               }
               $errors .= '</ul>';
               Yii::$app->session->setFlash("danger", $errors);
            return $this->redirect(['index']);
          }
        }
        $searchModel = new CursoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Curso model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if ( !\Yii::$app->user->can('curso-actualizar')) {
            throw new ForbiddenHttpException();
        }
        
        $model = $this->findModel($id);
        
        $model->scenario = 'actualizar';
        if ($model->load(Yii::$app->request->post())) {
            $model->archivo = UploadedFile::getInstance($model, 'archivo');
            
            // si subo otra imagen tengo que remplazar la anterior
            if($model->archivo) {
                // borro el archivo anterior
                unlink('img/cursos/' . $model->imagen);
                $model->imagen = Helper::limpiaUrl($model->curso . '.' . $model->archivo->extension);
            } else {
              // si cambia el nombre del curso renombro la imagen
              $model->imagen = Helper::limpiaUrl($model->curso . '.png');
              $oldImage = $model->oldAttributes['imagen'];
              rename('img/cursos/' . $oldImage, 'img/cursos/' . $model->imagen);
            }
            
            if ($model->save()) {
              if($model->archivo) {
                $model->archivo->saveAs( 'img/cursos/' . $model->imagen);
              }
              Yii::$app->session->setFlash('success', Yii::t('app', "Curso $model->curso actualizado satisfactoriamente"));
            } else {
              $errors = '<ul>';
                 foreach ($model->getErrors() as $key => $value) {
                     foreach ($value as $row => $field) {
                         $errors .= "<li>" . $field . "</li>";
                     }
                 }
                 $errors .= '</ul>';
                 Yii::$app->session->setFlash("danger", $errors);
              return $this->redirect(['view', 'id' => $model->id]);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Curso model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ( !\Yii::$app->user->can('curso-borrar')) {
            throw new ForbiddenHttpException();
        }
        
        $model = $this->findModel($id);
        
        try{
          if($model->delete()) {
            unlink('img/cursos/' . $model->imagen);
            Yii::$app->session->setFlash("success", Yii::t('app', "Curso $model->curso borrado satisfactoriamente!"));
          } else {
            $errors = '';
            foreach ($model->getErrors() as $key => $value) {
                foreach ($value as $row => $field) {
                    $errors .= $field . "<br>";
                }
            }
            Yii::$app->session->setFlash("danger", $errors);
          }
        } catch (\Exception $e) {
          Yii::$app->session->setFlash("warning", Yii::t('app', "Curso $model->curso no puede ser borrado!"));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Curso model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Curso the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Curso::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
