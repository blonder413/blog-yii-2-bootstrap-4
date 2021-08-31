<?php

namespace app\models;

use app\models\MiActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\db\Expression;
use yii\helpers\BaseUrl;

use Yii;

/**
 * This is the model class for table "articulo".
 *
 * @property int $id
 * @property int|null $numero
 * @property string $titulo
 * @property string $slug
 * @property string|null $tema
 * @property string $detalle
 * @property string $resumen
 * @property string|null $video
 * @property string|null $descarga
 * @property int $categoria_id
 * @property string $etiquetas
 * @property int $estado
 * @property int $vistas
 * @property int $descargas
 * @property int|null $curso_id
 * @property int $usuario_crea
 * @property string $fecha_crea
 * @property int $usuario_modifica
 * @property string $fecha_modifica
 *
 * @property Categoria $categoria
 * @property Curso $curso
 * @property User $usuarioCrea
 * @property User $usuarioModifica
 * @property Comentario[] $comentarios
 */
class Articulo extends MiActiveRecord
{
    const ESTADO_INACTIVO = 0; // 0
    const ESTADO_ACTIVO = 1;   // 1
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'articulo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero', 'categoria_id', 'estado', 'vistas', 'descargas', 'curso_id', 'usuario_crea', 'usuario_modifica'], 'integer'],
            [['titulo', 'detalle', 'video', 'categoria_id', 'etiquetas'], 'required'],
            [['detalle'], 'string'],
            [['fecha_crea', 'fecha_modifica'], 'safe'],
            [['titulo', 'slug'], 'string', 'max' => 150],
            [['tema', 'descarga'], 'string', 'max' => 100],
            [['resumen'], 'string', 'max' => 300],
            [['video', 'etiquetas'], 'string', 'max' => 255],
            [['titulo'], 'unique'],
            [['slug'], 'unique'],
            [['estado'], 'default', 'value' => self::ESTADO_ACTIVO],
            [['categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::className(), 'targetAttribute' => ['categoria_id' => 'id']],
            [['curso_id'], 'exist', 'skipOnError' => true, 'targetClass' => Curso::className(), 'targetAttribute' => ['curso_id' => 'id']],
            [['usuario_crea'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['usuario_crea' => 'id']],
            [['usuario_modifica'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['usuario_modifica' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'numero'            => 'Numero',
            'titulo'            => 'Título',
            'slug'              => 'Slug',
            'tema'              => 'Tema',
            'detalle'           => 'Detalle',
            'resumen'           => 'Resumen',
            'video'             => 'Video',
            'descarga'          => 'Descarga',
            'categoria_id'      => 'Categoría',
            'etiquetas'         => 'Etiquetas',
            'estado'            => 'Estado',
            'vistas'            => 'Vistas',
            'descargas'         => 'Descargas',
            'curso_id'          => 'Curso',
            'usuario_crea'      => 'Usuario Crea',
            'fecha_crea'        => 'Fecha Crea',
            'usuario_modifica'  => 'Usuario Modifica',
            'fecha_modifica'    => 'Fecha Modifica',
        ];
    }
/*
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['sluggable'] = [
            'class' => SluggableBehavior::className(),
                'attribute' => 'titulo',
                //'slugAttribute' => 'seo_slug',
        ];
        return $behaviors;
    }
*/    
    /**
     * @inheritdoc
     */
    
     public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'titulo',
                //'slugAttribute' => 'seo_slug',
            ],
//            OptimisticLockBehavior::className(),
        ];
    }
    
    
    /**
     * @inheritdoc
     */
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->estado           = self::ESTADO_ACTIVO;
                $this->usuario_crea     = Yii::$app->user->id;
                $this->fecha_crea       = new Expression('NOW()');
                $this->usuario_modifica = Yii::$app->user->id;
                $this->fecha_modifica   = new Expression('NOW()');
            } else {
                if ( isset( Yii::$app->user->id ) ) {
                    $this->usuario_modifica = Yii::$app->user->id;
                    $this->fecha_modifica   = new Expression('NOW()');
                }
            }
            return true;
        }
        return false;
    }
    

    /**
     * {@inheritdoc}
     */
    public static function find()
    {
        return new ArticuloQuery(get_called_class());
    }

    /**
     * Gets query for [[Categoria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categoria::className(), ['id' => 'categoria_id']);
    }

    /**
     * Gets query for [[Curso]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurso()
    {
        return $this->hasOne(Curso::className(), ['id' => 'curso_id']);
    }

    /**
     * Gets query for [[UsuarioCrea]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioCrea()
    {
        return $this->hasOne(User::className(), ['id' => 'usuario_crea']);
    }

    /**
     * Gets query for [[UsuarioModifica]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioModifica()
    {
        return $this->hasOne(User::className(), ['id' => 'usuario_modifica']);
    }

    /**
     * Gets query for [[Comentarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComentarios()
    {
        return $this->hasMany(Comentario::className(), ['articulo_id' => 'id']);
    }
    
    /**
     * cantidad de comentarios para cada artículo
     * @return \yii\db\ActiveQuery
     */
    public function getCantidadComentarios()
    {
        return $this->hasMany(Comentario::className(), ['articulo_id' => 'id'])
                    ->where("estado = " . Comentario::ESTADO_ACTIVO)
                    ->count();
    }
    
    /**
     * @return String the URL for the article detail
     */
    public function getUrl()
    {
        return BaseUrl::home() . "articulo/" . $this->slug;
    }
    
    public function getFechaTexto()
    {
        setlocale(LC_TIME, 'es_CO.UTF-8');
        //setlocale(LC_TIME, 'spa_ES');
        //setlocale(LC_TIME, 'spl_ES');
        //setlocale(LC_TIME, 'Spanish_Colombia');
        //setlocale(LC_TIME, 'es_CO.UTF-8');
//        return date('l, M j \d\e Y G:i', strtotime($this->created_at));
        return strftime("%c", strtotime($this->fecha_crea));
    }
}
