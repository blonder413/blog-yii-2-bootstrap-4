<?php
namespace app\rbac;
use yii\rbac\Rule;
use app\models\User;
/**
 * verifica que el usuario en sesión corresponda con el pasado por parámetro
 */
class YoRule extends Rule
{
    public $name = 'soyYo';
    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['user']) ? $params['user']->id == $user : false;
    }
}