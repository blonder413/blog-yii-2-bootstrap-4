<?php
namespace app\rbac;
use yii\rbac\Rule;

/**
 * Verifica que el usuario_crea corresponda con el usuario pasado por parámetro
 */
class AutorRule extends Rule
{
    public $name = 'esAutor';
    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed in Yii::$app->user->can() to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['model']) ? $params['model']->usuario_crea == $user : false;
    }
}