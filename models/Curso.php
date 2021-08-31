<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "curso".
 *
 * @property int $id
 * @property string $curso
 * @property string $slug
 * @property string $descripcion
 * @property string $imagen
 * @property int $usuario_crea
 * @property string $fecha_crea
 * @property int $usuario_modifica
 * @property string $fecha_modifica
 *
 * @property Articulo[] $articulos
 * @property User $usuarioCrea
 * @property User $usuarioModifica
 */
class Curso extends ActiveRecord
{
    public $archivo;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'curso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['curso', 'descripcion', 'imagen'], 'required', 'on' => 'crear'],
            [['curso', 'descripcion'], 'required', 'on' => 'actualizar'],
            [['descripcion'], 'string'],
            [['usuario_crea', 'usuario_modifica'], 'integer'],
            [['fecha_crea', 'fecha_modifica'], 'safe'],
            [['curso', 'slug'], 'string', 'max' => 100],
            [['imagen'], 'string', 'max' => 50],
            [['archivo'], 'image', 'extensions' => 'png'],
            [['curso'], 'unique'],
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
                'attribute' => 'curso',
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
            'curso' => 'Curso',
            'slug' => 'Slug',
            'descripcion' => 'Descripcion',
            'imagen' => 'Imagen',
            'usuario_crea' => 'Usuario Crea',
            'fecha_crea' => 'Fecha Crea',
            'usuario_modifica' => 'Usuario Modifica',
            'fecha_modifica' => 'Fecha Modifica',
        ];
    }

    /**
     * Gets query for [[Articulos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArticulos()
    {
        return $this->hasMany(Articulo::className(), ['curso_id' => 'id']);
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
}
