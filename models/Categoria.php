<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use Yii;
use Imagine\Image\Box;
use yii\imagine\Image;

/**
 * This is the model class for table "categoria".
 *
 * @property int $id
 * @property string $categoria
 * @property string $slug
 * @property string|null $imagen
 * @property string|null $descripcion
 * @property int $usuario_crea
 * @property string $fecha_crea
 * @property int $usuario_modifica
 * @property string $fecha_modifica
 *
 * @property User $usuarioCrea
 * @property User $usuarioModifica
 * @property Entrada[] $entradas
 */
class Categoria extends ActiveRecord
{
    public $archivo;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categoria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categoria', 'descripcion', 'imagen'], 'required', 'on' => 'crear'],
            [['categoria', 'descripcion'], 'required', 'on' => 'actualizar'],
            [['usuario_crea', 'usuario_modifica'], 'integer'],
            [['fecha_crea', 'fecha_modifica'], 'safe'],
            [['categoria', 'slug'], 'string', 'max' => 100],
            [['imagen'], 'string', 'max' => 50],
            [['archivo'], 'image', 'extensions' => 'png'],
            [['descripcion'], 'string', 'max' => 255],
            [['categoria'], 'unique'],
            [['slug'], 'unique'],
            [['usuario_crea'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['usuario_crea' => 'id']],
            [['usuario_modifica'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['usuario_modifica' => 'id']],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['fecha_crea', 'fecha_modifica'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['fecha_modifica'],
                ],
                'value' => new Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'usuario_crea',
                'updatedByAttribute' => 'usuario_modifica',
            ],
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'categoria',
                //'slugAttribute' => 'seo_slug',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categoria' => 'Categoria',
            'slug' => 'Slug',
            'imagen' => 'Imagen',
            'descripcion' => 'Descripcion',
            'usuario_crea' => 'Usuario Crea',
            'fecha_crea' => 'Fecha Crea',
            'usuario_modifica' => 'Usuario Modifica',
            'fecha_modifica' => 'Fecha Modifica',
        ];
    }

    public function resizeImage($path, $width, $height)
    {
        $max_width  = $width;
        $max_height = $height;

        $imagine = Image::getImagine();
        $time_limit = ini_get('max_execution_time');
        $memory_limit = ini_get('memory_limit');

        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $resizedImage = $imagine->open($path);
        $sizeR     = $resizedImage->getSize();
        set_time_limit($time_limit);
        ini_set('memory_limit', $memory_limit);
        $width_orig    = $sizeR->getWidth();
        $height_orig   = $sizeR->getHeight();

        $x_ratio = $max_width / $width_orig;
        $y_ratio = $max_height / $height_orig;

        if ( ($width_orig <= $max_width) && ($height_orig <= $max_height) ) {
            $tn_width = $width_orig;
            $tn_height = $height_orig;
        } else if (($x_ratio * $height_orig) < $max_height) {
            $tn_height = ceil($x_ratio * $height_orig);
            $tn_width = $max_width;
        } else {
            $tn_width = ceil($y_ratio * $width_orig);
            $tn_height = $max_height;
        }

        $size = new Box($tn_width, $tn_height);
        $resizedImage->resize($size)->save($path);
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
     * Gets query for [[Entradas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEntradas()
    {
        return $this->hasMany(Entrada::className(), ['categoria_id' => 'id']);
    }
}
