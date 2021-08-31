<?php

namespace app\models;
use app\models\User;
use Yii;

/**
 * This is the model class for table "transmision".
 *
 * @property int $id
 * @property string $titulo
 * @property string $descripcion
 * @property string|null $video
 * @property string $inicio
 * @property string $fin
 * @property int $usuario_crea
 * @property string $fecha_crea
 * @property int $usuario_modifica
 * @property string $fecha_modifica
 *
 * @property User $usuarioCrea
 * @property User $usuarioModifica
 */
class Transmision extends MiActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transmision';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['titulo', 'descripcion', 'inicio', 'fin'], 'required'],
            [['descripcion'], 'string'],
            [['inicio', 'fin', 'fecha_crea', 'fecha_modifica'], 'safe'],
            [['usuario_crea', 'usuario_modifica'], 'integer'],
            [['titulo'], 'string', 'max' => 100],
            [['video'], 'string', 'max' => 255],
            [['usuario_crea'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['usuario_crea' => 'id']],
            [['usuario_crea'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['usuario_crea' => 'id']],
            [
                ['inicio'], 'compare', 'compareAttribute' => 'fin', 'operator' => '<',
                //'when' => function($model){
                //  return $this->inicio < $this->fin;
                //},
                //'whenClient'  => "function (attribute, value) {
                //  return $('#transmision-inicio').val() < $('#transmision-fin').val();
                //}"
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
            'titulo' => 'Titulo',
            'descripcion' => 'Descripcion',
            'video' => 'Video',
            'inicio' => 'Inicio',
            'fin' => 'Fin',
            'usuario_crea' => 'Usuario Crea',
            'fecha_crea' => 'Fecha Crea',
            'usuario_modifica' => 'Usuario Modifica',
            'fecha_modifica' => 'Fecha Modifica',
        ];
    }

    /**
     * Gets query for [[UsuarioCrea]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioCrea()
    {
        return $this->hasOne(User::class, ['id' => 'usuario_crea']);
    }

    /**
     * Gets query for [[UsuarioModifica]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioModifica()
    {
        return $this->hasOne(User::class, ['id' => 'usuario_modifica']);
    }
}
