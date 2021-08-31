<?php

namespace app\controllers;

use Yii;
use app\models\ArticuloSearch;
use app\models\Categoria;
use app\models\CategoriaSearch;
use app\models\Helper;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use ZipArchive;

/**
 * CategoriaController implements the CRUD actions for Categoria model.
 */
class CategoriasController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'procesar', 'buscar', 'update', 'delete'],
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
     * Lists all Categoria models.
     * @return mixed
     */
    public function actionIndex()
    {
        if ( !\Yii::$app->user->can('categoria-listar')) {
            throw new ForbiddenHttpException();
        }
        
        $searchModel = new CategoriaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'           => $searchModel,
            'dataProvider'          => $dataProvider,
        ]);
    }

    /**
     * Displays a single Categoria model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if ( !\Yii::$app->user->can('categoria-ver')) {
            throw new ForbiddenHttpException();
        }

        $model = $this->findModel($id);
        $searchArticulo = new ArticuloSearch();
        $dataProviderArticulo = $searchArticulo->search(
            Yii::$app->request->queryParams,
            $model->id,
        );
        
        return $this->render('view', [
            'dataProviderArticulo'  => $dataProviderArticulo,
            'model'                 => $model,
            'searchArticulo'        => $searchArticulo,
        ]);
    }

    /**
     * Creates a new Categoria model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if ( !\Yii::$app->user->can('categoria-crear')) {
            throw new ForbiddenHttpException();
        }
        
        $model = new Categoria(['scenario' => 'crear']);
        if ($model->load(Yii::$app->request->post())) {
          $model->archivo = UploadedFile::getInstance($model, 'archivo');
          $model->imagen = Helper::limpiaUrl($model->categoria . '.' . $model->archivo->extension);
          if ($model->save()) {
            
            $model->archivo->saveAs( 'img/categorias/' . $model->imagen);
            $model->resizeImage('img/categorias/' . $model->imagen, 300, 300);

            Yii::$app->session->setFlash('success', Yii::t('app', "Categoría $model->categoria creada satisfactoriamente"));
            return $this->redirect(['index']);
          } else {
            $errors = '<ul>';
               foreach ($model->getErrors() as $key => $value) {
                   foreach ($value as $row => $field) {
                       $errors .= "<li>" . $field . "</li>";
                   }
               }
               $errors .= '</ul>';
               Yii::$app->session->setFlash("danger", $errors);
            return $this->render('create', [
                'model' => $model,
            ]);
          }
        }
        $searchModel = new CategoriaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Categoria model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if ( !\Yii::$app->user->can('categoria-actualizar')) {
            throw new ForbiddenHttpException();
        }
        
        $model = $this->findModel($id);
                
        $model->scenario = 'actualizar';
        if ($model->load(Yii::$app->request->post())) {
            $model->archivo = UploadedFile::getInstance($model, 'archivo');
            
            // si subo otra imagen tengo que remplazar la anterior
            if($model->archivo) {
                // borro el archivo anterior
                unlink('img/categorias/' . $model->imagen);
                $model->imagen = Helper::limpiaUrl($model->categoria . '.' . $model->archivo->extension);
            } else {
              // si cambia el nombre del curso renombro la imagen
              $model->imagen = Helper::limpiaUrl($model->categoria . '.png');
              $oldImage = $model->oldAttributes['imagen'];
              rename('img/categorias/' . $oldImage, 'img/categorias/' . $model->imagen);
            }
            
            if ($model->save()) {
              if($model->archivo) {
                $model->archivo->saveAs( 'img/categorias/' . $model->imagen);
              }
              Yii::$app->session->setFlash('success', Yii::t('app', "Categoría $model->categoria actualizada satisfactoriamente"));
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
     * Deletes an existing Categoria model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ( !\Yii::$app->user->can('categoria-borrar')) {
            throw new ForbiddenHttpException();
        }
        
        $model = $this->findModel($id);
        
        try{  
            if($model->delete()) {
              unlink('img/categorias/' . $model->imagen);
              Yii::$app->session->setFlash("success", Yii::t('app', "Categoría $model->categoria borrada satisfactoriamente!"));
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
          Yii::$app->session->setFlash("warning", Yii::t('app', "La categoría $model->categoria no puede ser borrada!"));
        }
        return $this->redirect(['index']);
    }

    /**
     * exportar las imágenes en un zip
     * @return zip archivo comprimido con las imágenes
     */
    public function actionProcesar()
    {
        if ( !\Yii::$app->user->can('categoria-procesar')) {
            throw new ForbiddenHttpException();
        }

        if (Yii::$app->request->post('submit') == 'img') {
            $post = Yii::$app->request->post('selection');

            $zip = new ZipArchive();
            $zipFileName = "img_categorias.zip";

            if ($zip->open($zipFileName, ZipArchive::CREATE) !== TRUE) {
                exit("cannot open <$zipFileName>\n");
            }

            foreach ($post as $key => $value) {
                $model = $this->findModel($value);
                                
                /*
                // Error if one file is not exists
                if (!$zip->addFile('img/categories/' . $model->image, '/' . $model->image)) {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'No se encontró la imagen ' . $model->image));
                    $zip->close();
                    unlink($zipFileName);
                    return $this->redirect(Yii::$app->request->referrer);
                }
                */

                $file = 'img/categorias/' . $model->imagen;
                if (is_file($file) && is_readable($file)) {
                    $zip->addFile($file, '/' . $model->imagen);
                }
            }

            $zip->close();

            ///Then download the zipped file.
            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename='.$zipFileName);
            header('Content-Length: ' . filesize($zipFileName));

//            return Yii::$app->response->sendFile($zipFileName);

            readfile($zipFileName);

            unlink($zipFileName);
        }
    }

    /**
     * Categorías para los select
     * @param String $q categoría a buscar
     * @return Array Listado de las categorías
     */
    public function actionBuscar($q = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;

            $query->select(['categoria as text', 'id'])
                    ->from('categoria')
                    ->orFilterWhere(['like', 'categoria', $q])
                    ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }

        return $out;
    }
    
    /**
     * Finds the Categoria model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Categoria the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Categoria::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}
