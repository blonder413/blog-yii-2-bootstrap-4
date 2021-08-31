<?php
namespace app\models;
use yii\db\ActiveQuery;
class ComentarioQuery extends ActiveQuery
{
    // conditions appended by default (can be skipped)
    public function init()
    {
        // $this->andOnCondition(['deleted' => false]);
        parent::init();
    }
    
    /**
     * Genera la condición para filtrar los comentarios según su estado
     * @param boolean $estado estado del comentario
     * @return mixed Condicional
     */
    public function activo($estado = true)
    {
        return $this->andOnCondition(['comentario.estado' => $estado]);
    }
}