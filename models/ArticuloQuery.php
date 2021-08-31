<?php
/**
 * Esta clase posee métodos para extender ActiveRecord
 */
namespace app\models;
use yii\db\ActiveQuery;
class ArticuloQuery extends ActiveQuery
{
    // conditions appended by default (can be skipped)
    public function init()
    {
        // $this->andOnCondition(['deleted' => false]);
        parent::init();
    }
    
    /**
     * Este método filtra los artículos activos
     * @param boolean $estado Estado del arttículo
     * @return mixed condición que el estado sea el pasado
     */
    public function active($estado = true)
    {
        return $this->andOnCondition(['articulo.estado' => $estado]);
    }
}