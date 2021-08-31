<?php
namespace app\commands;

use Yii;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\helpers\Console;
use yii\rbac\DbManager;

class RbacController extends Controller
{
    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }
        return $authManager;
    }
    
    public function actionInit()
    {
//        $transaction = Yii::$app->db->beginTransaction();
//        try {
            $auth = $this->getAuthManager();
            $auth->removeAll();

            $tables = ['articulo', 'categoria', 'comentario', 'curso', 'transmision', 'user'];
            $permissions = ['crear', 'eliminar', 'actualizar', 'ver', 'listar'];
            $role_admin = $auth->createRole('admin');
            $role_admin->description = 'Administra toda la información';
            $auth->add($role_admin);
            $this->stdout("El rol admin ha sido creado\n", Console::FG_GREEN);
            
            for ($i = 0; $i < count($tables); $i++) {
                $this->stdout("creando roles y permisos para la tabla $tables[$i] \n", Console::FG_YELLOW);
                $role = $auth->createRole($tables[$i] . '-admin');
                $role->description = 'Administra todas las operaciones de la tabla ' . $tables[$i];
                $auth->add($role);
                $auth->addChild($role_admin, $role);
                for ($j = 0; $j < count($permissions); $j++) {
                    $permission = $auth->createPermission($tables[$i] . '-' . $permissions[$j]);
                    $permission->description = $permissions[$j] . ' registros en la tabla ' . $tables[$i];
                    $auth->add($permission);
                    $auth->addChild($role, $permission);
                }
                
                if ($tables[$i] == 'articulo' or $tables[$i] == 'comentario' or $tables[$i] == 'user') {
                    $permission_status = $auth->createPermission($tables[$i] . '-cambiar-estado');
                    $permission_status->description = 'Cambia el estado de la tabla ' . $tables[$i];
                    $auth->add($permission_status);
                    $auth->addChild($role, $permission_status);
                    $auth->addChild($permission_status, $permission);
                }
                $this->stdout("Se han creado los roles y permisos para la tabla $tables[$i] \n", Console::FG_GREEN);
                
//                $permission = $auth->createPermission('categoria-procesar');
//                $permission->description = 'exportar imágenes de la tabla categoría';
//                $auth->add($permission);
//                $auth->addChild('categoria-admin', $permission);
            }
            //------------------------------------------------------------------------------------------------

//            $transaction->commit();
//        } catch (\Exception $e) {
//            $transaction->rollBack();
//            throw $e;
//        }
    }

    public function actionReglas()
    {
        $auth = $this->getAuthManager();
        
        // solo puede acceder a sus propios registros
        $rule = new \app\rbac\AutorRule;
        $auth->add($rule);
        
        // agrega el permiso "propio-registro" y le asocia la regla.
        $propioRegistro = $auth->createPermission('propio-registro');
        $propioRegistro->description = 'Solo puede acceder a sus registros propios';
        $propioRegistro->ruleName = $rule->name;
        $auth->add($propioRegistro);

        // "propioRegistro" será utilizado desde "artculoArtualizar, articuloEliminar y articuloVer"
        $articuloActualizar = $auth->getPermission('articulo-actualizar');
        $auth->addChild($propioRegistro, $articuloActualizar);

        $articuloEliminar = $auth->getPermission('articulo-eliminar');
        $auth->addChild($propioRegistro, $articuloEliminar);

        $articuloVer = $auth->getPermission('articulo-ver');
        $auth->addChild($propioRegistro, $articuloVer);
        
        // los usuarios solo puede ver su propio perfil
        $rule = new \app\rbac\YoRule;
        $auth->add($rule);

        $this->stdout("Se han creado las reglas \n", Console::FG_GREEN);
    }
}