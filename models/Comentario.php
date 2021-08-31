<?php

namespace app\models;

use app\models\ComentarioQuery;
use app\models\Seguridad;
use Yii;

/**
 * This is the model class for table "comentario".
 *
 * @property int $id
 * @property string $nombre
 * @property string $correo
 * @property string|null $web
 * @property string|null $rel
 * @property string $comentario
 * @property string $fecha
 * @property int $articulo_id
 * @property int $estado
 * @property string|null $ip
 * @property string|null $puerto
 *
 * @property Articulo $articulo
 */
class Comentario extends \yii\db\ActiveRecord
{
    const ESTADO_ACTIVO = 1;
    const ESTADO_INACTIVO = 0;
    public $verifyCode;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comentario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'comentario', 'fecha', 'articulo_id'], 'required'],
            [['comentario'], 'string'],
            [['fecha'], 'safe'],
            [['articulo_id', 'estado'], 'integer'],
            [['nombre'], 'string', 'max' => 150],
            [['correo', 'web'], 'string', 'max' => 100],
            ['web', 'url', 'defaultScheme' => 'http', 'message' => 'Por favor introduzca la URL completa, ej: www.blonder413.com'],
            [['rel'], 'string', 'max' => 20],
            [['ip'], 'string', 'max' => 15],
            [['puerto'], 'string', 'max' => 5],
            [['verifyCode'], 'captcha', 'on'=>'comentar'],
            [['estado'], 'default', 'value' => self::ESTADO_INACTIVO],
            [['articulo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Articulo::className(), 'targetAttribute' => ['articulo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'nombre'        => 'Nombre',
            'correo'        => 'Correo',
            'web'           => 'Web',
            'rel'           => 'Rel',
            'comentario'    => 'Comentario',
            'fecha'         => 'Fecha',
            'articulo_id'   => 'Artículo',
            'estado'        => 'Estado',
            'ip'            => 'Ip',
            'puerto'        => 'Puerto',
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        /*
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->correo = Seguridad::encriptar($this->correo);
            }
            return true;
        }
        */
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->correo = Seguridad::encriptar($this->correo);
            }
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public static function find()
    {
        return new ComentarioQuery(get_called_class());
    }

    /**
     * Gets query for [[Articulo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArticulo()
    {
        return $this->hasOne(Articulo::className(), ['id' => 'articulo_id']);
    }
}
