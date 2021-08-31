<?php

namespace app\controllers;

use app\models\Articulo;
use app\models\Categoria;
use app\models\Comentario;
use app\models\ComentarioSearch;
use app\models\ContactForm;
use app\models\Curso;
use app\models\Helper;
use app\models\LoginForm;
use app\models\PasswordResetRequestForm;
use app\models\ResendVerificationEmailForm;
use app\models\ResetPasswordForm;
use app\models\Seguridad;
use app\models\SignupForm;
use app\models\Transmision;
use app\models\User;
use app\models\VerifyEmailForm;
use Yii;
use yii\data\Pagination;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;

class SiteController extends Controller
{
//    public $layout = 'adminLTE/main';
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'admin'],
                'rules' => [
                    [
                        'actions' => ['admin','logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['admin'],
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $query = Articulo::find()->active();

        $pagination = new Pagination([
            'defaultPageSize'   => 15,
            'forcePageParam'    => false,   // para que no salga la página 1 en la URL
            'totalCount'        => $query->count(),
        ]);

        $articulos = $query->orderBy('id desc')
//                    ->where('estado = TRUE')
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->all();

        $categorias = Categoria::find()->orderBy('categoria asc')->all();

        $mas_visitado = Articulo::find()->orderBy('vistas desc')->one();
        $aleatorios = Articulo::find()->orderBy('rand()')->limit(2)->all();

        return $this->render('index', [
            'aleatorios'    => $aleatorios,
            'articulos'     => $articulos,
            'categorias'    => $categorias,
            'mas_visitado'  => $mas_visitado,
            'pagination'    => $pagination,
        ]);
    }
    
    /**
     * listar un artículo por su slug
     * @param string $slug slug del artículo
     * @return object
     */
    public function actionArticulo($slug)
    {
//        $this->layout = 'blue/main';
        $articulo = Articulo::find()->where('slug = :slug', [':slug' => $slug])->one();
        
        if (!is_object($articulo)) {
            throw new NotFoundHttpException();
        }

        $curso = Curso::findOne($articulo->curso_id);

        $articuloSiguiente  = Articulo::find()->where(
                            "curso_id = :curso_id AND numero = :numero",
                            [":curso_id" => $articulo->curso_id, ":numero" => $articulo->numero + 1]
                        )->one();

        $articuloAnterior   = Articulo::find()->where(
                            "curso_id = :curso_id AND numero = :numero",
                            [":curso_id" => $articulo->curso_id, ":numero" => $articulo->numero - 1]
                        )->one();

        $categorias = Categoria::find()->orderBy('categoria asc')->all();

        // AUMENTAR EL CAMPO visitas en 1
        if (Yii::$app->user->isGuest) {
            try {
                $articulo->vistas += 1;
//                $article->updateCounters(["views" => 1]);
                $articulo->update();
            } catch(yii\db\StaleObjectException $e) {
                throw $e;	// the register has been modified for multiples users              
            }
        }

        $etiquetas = explode(", ", $articulo->etiquetas);

        $comentario = new Comentario(['scenario' => 'comentar']);

        $mas_visitados = Articulo::find()->orderBy('vistas desc')->limit(5)->all();

//        if ( Yii::$app->request->isAjax && $comentario->load(Yii::$app->request->post()) ) {

        if ($comentario->load(Yii::$app->request->post())) {
//            date_default_timezone_set('America/Bogota');
            $comentario->fecha          = new Expression('NOW()');
            $comentario->articulo_id    = $articulo->id;
            $comentario->ip             = Helper::getRealIP();
            $comentario->puerto         = $_SERVER['REMOTE_PORT'];
            $comentario->rel            = "nofollow";
            
//            if (!$comentarop->validate()) {
//                Yii::$app->response->format = Response::FORMAT_JSON;
//                return \yii\widgets\ActiveForm::validate($comentario);
//            }

            $listaNegra = [
            ];
            
            $comentarioAnterior = Comentario::find()->where(
                "correo = :correo AND articulo_id = :articulo AND comentario = :comentario",
                [ 
                    ":correo" => Seguridad::encriptar($comentario->correo), 
                    ":articulo" => $comentario->articulo_id, 
                    ":comentario" => $comentario->comentario 
                ]
            )->count();
            
            if ( in_array($comentario->ip, $listaNegra) or $comentarioAnterior > 0 ) {
                $comentario = new Comentario(['scenario' => 'comentar']);
                Yii::$app->session->setFlash(
                    "success", 
                    "Gracias por su opinión. Su comentario será publicado una vez que sea moderado!"
                );
            } elseif ($comentario->save()) {
                $comentario = new Comentario(['scenario' => 'comentar']);
                Yii::$app->session->setFlash(
                    "success", 
                    "Gracias por su opinión. Su comentario será publicado una vez que sea moderado!"
                );
            } else {
                Yii::$app->session->setFlash("error", "Su comentario no pudo ser registrado!");
            }
        }

        return $this->render('articulo', [
            'articulo'          => $articulo,
            'categorias'        => $categorias,
            'comentario'        => $comentario,
            'curso'             => $curso,
            'mas_visitados'     => $mas_visitados,
            'articuloSiguiente' => $articuloSiguiente,
            'articuloAnterior'  => $articuloAnterior,
            'etiquetas'         => $etiquetas,
        ]);
    }
    
    /**
     * Descargar el archivo del artículo
     * si no es un usuario que haya iniciado sesión se cuenta descargas
     * en la tabla artículo
     * @param string $slug Slug del artículo
     * @return archivo a descargar
     */
    public function actionDescarga($slug)
    {
        $articulo = Articulo::find()
                    ->where( 'slug = :slug', [':slug' => $slug] )->one();
        
        if (empty($articulo->descarga)) {
            Yii::$app->session->setFlash(
                    'download_error', 
                    'No existe una descarga para este artículo'
            );
            return $this->redirect(['articulo/' . $slug]);
        } else {
            if (Yii::$app->user->isGuest) {
                $articulo->descargas += 1;
                $articulo->update();
            }
            
            $archivo = Html::encode($articulo->descarga);
            
            return $this->redirect($archivo);
        }
    }
    
    /**
     * Convertir a PDF un artículo
     * @param string $slug slug del artículo
     * @return PDF archivo PDF con la información del artículo
     * @throws NotFoundHttpException
     */
    public function actionPdf($slug)
    {
        $this->layout = 'periodico/pdf';

        $articulo = Articulo::find()
                    ->where( 'slug = :slug', [':slug' => $slug] )->one();
    
        if (!$articulo) {
            throw new NotFoundHttpException();
        }
        
        // get your HTML raw content without any layouts or scripts
        $content = $this->render('pdf', ['articulo' => $articulo]);
        
        $footer = $this->renderPartial('/layouts/periodico/_footerpdf');
        $header = $this->renderPartial('/layouts/periodico/_headerpdf');
        $pdf = Yii::$app->pdf;
        $pdf->content = $content;
        $pdf->marginTop = 30;
        $pdf->marginBottom  = 35;
        $pdf->marginLeft    = 13;
        $pdf->marginRight   = 13;
        $pdf->cssFile	= 'css/azul/pdf.css';
//        $pdf->destination = 'D';
//        $pdf->destination = 'DEST_DOWNLOAD';
        $pdf->filename = $articulo->titulo . 'pdf';
        
        $pdf->methods = [
            'SetFooter' => [$footer . '{PAGENO}'],
            'SetHeader' => [$header],
//            'SetWatermarkText'  => ['blonder'],
//            'SetWatermarkImage' => Yii::$app->homeUrl . '/img/logo.png',
            'SetWatermarkImage' => ['img/estampado.png'],
            
        ];

        // return the pdf output as per the destination setting
        return $pdf->render();
    }
    
    /**
     * listar todos los artículos de un autor
     * @param String $slug nombre de usuario del autor
     * @return array artículos escritos por el autor especificado
     */
    public function actionAutor($autor)
    {
        $user = User::find()->where('name = :nombre', [':nombre' => urldecode($autor)])->one();
        $query = Articulo::find()->where( 'usuario_crea = :id', [':id' => $user->id] );
        $pagination     = new Pagination([
            'defaultPageSize'   => 15,
            'totalCount'        => $query->count(),
        ]);
        
        $articulos      = Articulo::find()
                            ->where( 'usuario_crea = :id', [':id' => $user->id])
                            ->andWhere('estado = TRUE')
                            ->orderBy('id desc')
                            ->offset($pagination->offset)
                            ->limit($pagination->limit)
                            ->all();
        
        $categorias     = Categoria::find()->orderBy('categoria asc')->all();
        $mas_visitado  = Articulo::find()->orderBy('vistas desc')->one();
        $aleatorios = Articulo::find()->orderBy('rand()')->limit(2)->all();
        
        return $this->render(
            'autor',
            [
                'aleatorios'    => $aleatorios,
                'articulos'     => $articulos,
                'categorias'    => $categorias,
                'mas_visitado'  => $mas_visitado,
                'pagination'    => $pagination,
                'user'          => $user,
            ]
        );
    }
    
    /**
     * listar los artículos de una categoría
     * @param string $slug slug de la categoría
     * @return object
     */
    public function actionCategoria($slug)
    {
        $categoria = Categoria::find()
                    ->where("slug = :slug", [':slug' => $slug])->one();

        $query = Articulo::find()
                ->where("categoria_id = :id", [':id' => $categoria->id]);

        $pagination = new Pagination([
            'defaultPageSize'   => 15,
            'totalCount'        => $query->count(),
        ]);

        $articulos = Articulo::find()
                    ->where("categoria_id = :id", [':id' => $categoria->id])
                    ->orderBy("id desc")
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->all();

        $categorias     = Categoria::find()->orderBy('categoria asc')->all();
        $mas_visitado  = Articulo::find()->orderBy('vistas desc')->one();
        $aleatorios = Articulo::find()->orderBy('rand()')->limit(2)->all();

        return $this->render(
            'categoria',
            [
                'aleatorios'    => $aleatorios,
                'articulos'     => $articulos,
                'categorias'    => $categorias,
                'categoria'     => $categoria,
                'mas_visitado'  => $mas_visitado,
                'pagination'    => $pagination,
            ]
        );
    }
    
    /**
     * find an article by tag
     * @param string $tag tag of article
     * @return object
     */
    public function actionEtiqueta($etiqueta)
    {
        $query = Articulo::find()->where(["like", "etiquetas", $etiqueta]);

        $pagination = new Pagination([
            'defaultPageSize'   => 5,
            'totalCount'        => $query->count(),
        ]);

        $articulos = Articulo::find()
                ->where(["like", "etiquetas", $etiqueta])
                ->orderBy("id desc")
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();

        $categorias     = Categoria::find()->orderBy('categoria asc')->all();
        $mas_visitado  = Articulo::find()->orderBy('vistas desc')->one();
        $aleatorios = Articulo::find()->orderBy('rand()')->limit(2)->all();

        return $this->render(
            'etiqueta',
            [
                'aleatorios'    => $aleatorios,
                'articulos'     => $articulos,
                'categorias'    => $categorias,
                'etiqueta'      => $etiqueta,
                'mas_visitado'  => $mas_visitado,
                'pagination'    => $pagination,
            ]
        );
    }
    
    /**
     * Muestra página cuando el sitio está fuera de línea
     *
     * @return string
     */
    public function actionOffline()
    {
        $this->layout = false;
        return $this->render("offline");
    }
    
    /**
     * Displays portafolio.
     *
     * @return string
     */
    public function actionPortafolio()
    {
        $categorias = Categoria::find()->orderBy('categoria asc')->all();
        $mas_visitados = Articulo::find()->orderBy('vistas desc')->limit(5)->all();

        return $this->render(
            'portafolio',
            [
                'categorias'    => $categorias,
                'mas_visitados'  => $mas_visitados,
            ]
        );
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = 'adminLTE3/main-login';
        
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $categorias = Categoria::find()->orderBy('categoria asc')->all();
        $mas_visitados = Articulo::find()->orderBy('vistas desc')->limit(5)->all();
        $model = new ContactForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'categorias'    => $categorias,
            'mas_visitados'  => $mas_visitados,
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        $categorias = Categoria::find()->orderBy('categoria asc')->all();
        $mas_visitados = Articulo::find()->orderBy('vistas desc')->limit(5)->all();

        return $this->render(
            'about',
            [
                'categorias'    => $categorias,
                'mas_visitados'  => $mas_visitados,
            ]
        );
    }
    
    /**
     * Displays streaming page.
     *
     * @return string
     */
    public function actionTransmision()
    {
        $ahora = date("Y-m-d H:i:s");

        $transmision = Transmision::find()
                    ->where('inicio <= :ahora', [':ahora' => $ahora])
                    ->andWhere("fin >= :ahora", [":ahora" => $ahora])
                    ->one();

        $articulos = $mas_visitados = Articulo::find()->where('categoria_id = 15')
                    ->orderBy('rand()')->limit(4)->all();
        $categorias = Categoria::find()->orderBy('categoria asc')->all();
        $mas_visitados = Articulo::find()->orderBy('vistas desc')->limit(5)->all();

        $siguiente_transmision = Transmision::find()
                    ->where('inicio > :ahora', [':ahora' => $ahora])
                    ->orderBy('fin asc')
                    ->one();

        return $this->render(
            'transmision',
            [
                'articulos'             => $articulos,
                'categorias'            => $categorias,
                'mas_visitados'         => $mas_visitados,
                'siguiente_transmision' => $siguiente_transmision,
                'transmision'           => $transmision,
            ]
        );
    }
    
    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        $categorias     = Categoria::find()->orderBy('categoria asc')->all();
        
        if ($model->load(Yii::$app->request->post())) {
            
            $model->file = UploadedFile::getInstance($model, 'file');
        
            $model->photo = Helper::limpiaUrl($model->username . '.' . $model->file->extension);
            
            if ($user = $model->signup()) {
                
                $model->file->saveAs( 'img/users/' . $model->photo);

                $user = User::find()->where(['username' => $model->username])->one();

                $auth = \Yii::$app->authManager;

                $autorPermission = $auth->getPermission('articulo-listar');
                $auth->assign($autorPermission, $user->getId());

                $autorPermission = $auth->getPermission('articulo-crear');
                $auth->assign($autorPermission, $user->getId());
                
                $autorPermission = $auth->getPermission('propio-registro');
                $auth->assign($autorPermission, $user->getId());

                Yii::$app->session->setFlash(
                    'signup', 
                    'Gracias por registrarse. Por favor verifique la bandeja de entrada de su correo electrónico.'
                );
//                if (Yii::$app->getUser()->login($user)) {
//                    return $this->goHome();
                    return $this->redirect('login');
//                }
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
            }
        }

        return $this->render('signup', [
            'categorias'    => $categorias,
            'model'         => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        $categorias     = Categoria::find()->orderBy('categoria asc')->all();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash(
                    'request', 
                    'Revise su correo electrónico para obtener más instrucciones.'
                );

//                return $this->goHome();
                return $this->redirect('login');
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'categorias'    => $categorias,
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('new-password', 'Nueva contraseña establecida.');

//            return $this->goHome();
            return $this->redirect('login');
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash(
                    'success', 
                    'Su correo electrónico ha sido confirmado!'
                );
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        $categorias     = Categoria::find()->orderBy('categoria asc')->all();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash(
                    'request', 
                    'Revise su correo electrónico para obtener más instrucciones.'
                );
//                return $this->goHome();
                return $this->redirect('login');
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'categorias'    => $categorias,
            'model' => $model
        ]);
    }
    
    /**
     * Listar todos los cursos
     * @return objects array
     */
    public function actionCursos()
    {
        $categorias     = Categoria::find()->orderBy('categoria asc')->all();
        $mas_visitados = Articulo::find()->orderBy('vistas desc')->limit(5)->all();
        $cursos         = Curso::find()->all();

        return $this->render(
            'cursos',
            [
                'categorias'    => $categorias,
                'cursos'        => $cursos,
                'mas_visitados' => $mas_visitados,
            ]
        );
    }
    
    /**
     * Lista todos los artículos de un curso
     * @param String $slug slug del curso
     * @return Array lista de artículos del curso seleccionado
     */
    public function actionCurso($slug)
    {
        $categorias = Categoria::find()->orderBy('categoria asc')->all();
        $curso      = Curso::find()->where("slug = :slug", [":slug" => $slug])->one();

        if (!$curso) {
            throw new NotFoundHttpException();
        }
        
        $query          = Articulo::find()->where("curso_id = :curso", [":curso" => $curso->id]);
        $pagination     = new Pagination([
            'defaultPageSize'   => 15,
            'totalCount'        => $query->count(),
        ]);

        $articulos      = Articulo::find()
                            ->where("curso_id = :curso", [":curso" => $curso->id])
                            ->offset($pagination->offset)
                            ->limit($pagination->limit)
                            ->all();
        $mas_visitados  = Articulo::find()->orderBy('vistas desc')->limit(5)->all();
        
        $cursos         = Curso::find()
                        ->where("slug <> :slug", [":slug" => $curso->slug])
                        ->limit(4)->orderBy("rand()")->all();

        return $this->render(
            'curso',
            [
                'articulos'     => $articulos,
                'categorias'    => $categorias,
                'curso'         => $curso,
                'cursos'        => $cursos,
                'mas_visitados' => $mas_visitados,
                'pagination'    => $pagination,
            ]
        );
    }
    
    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAdmin()
    {
        $this->layout = 'adminLTE3/main';
        
        $model = Articulo::find()->select(['titulo', 'vistas'])
                ->orderBy('vistas desc')
                ->limit(10)->all();
        
        foreach ($model as $key => $value) {
            //$name[] = Helper::myTruncate($value->title, 5);
            $mas_visitados[] = [$value->titulo, $value->vistas];
        }
        
        $pendientes = Comentario::find()->where(['estado' => Comentario::ESTADO_INACTIVO])->count();
        
        $searchModel = new ComentarioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render(
            'admin',
            [
                'mas_visitados' => $mas_visitados,
                'pendientes'    => $pendientes,
                'searchModel'   => $searchModel,
                'dataProvider'  => $dataProvider,
            ]
        );
    }
}
