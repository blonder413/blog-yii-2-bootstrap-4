<?php
/**
 * This class have the behaviors method.
 * This method set values for created_by, created_at, updated_by, updated_at automatically
 * @author blonder413
 */
namespace app\models;
use \yii\db\Expression;
use \yii\behaviors\BlameableBehavior;
use \yii\behaviors\TimestampBehavior;
use \yii\db\ActiveRecord;
use Yii;
class MiActiveRecord extends ActiveRecord{
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'fecha_crea',
                'updatedAtAttribute' => 'fecha_modifica',
                'value' => new Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'usuario_crea',
                'updatedByAttribute' => 'usuario_modifica',
            ],
        ];
    }
}