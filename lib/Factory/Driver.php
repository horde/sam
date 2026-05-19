<?php
use Horde\Injector\Injector;

/**
 * Sam_Driver factory.
 *
 * Copyright 2011-2026 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (GPL). If you
 * did not receive this file, see http://www.horde.org/licenses/gpl.
 *
 * @author   Jan Schneider <jan@horde.org>
 * @category Horde
 * @license  http://www.horde.org/licenses/gpl GPL
 * @package  Sam
 */
class Sam_Factory_Driver extends Horde_Core_Factory_Injector
{
    /**
     * @var array
     */
    private $_instances = [];

    /**
     * Return an Sam_Driver_Base instance.
     *
     * @return Sam_Driver_Base
     * @throws Sam_Exception
     */
    public function create(Horde_Injector|Injector $injector)
    {
        $backend = Sam::getPreferredBackend();
        $signature = hash('md5', serialize($backend));

        if (empty($this->_instances[$signature])) {
            $user = Sam::mapUser($backend['hordeauth']);

            switch ($backend['driver']) {
                case 'Amavisd_Sql':
                case 'Spamd_Sql':
                    $db_params = array_merge(Horde::getDriverConfig(null, 'sql'), $backend['params']);
                    unset($db_params['table_map']);
                    try {
                        $db = $injector->getInstance('Horde_Core_Factory_Db')
                            ->create('sam', $db_params);
                    } catch (Horde_Exception $e) {
                        throw new Sam_Exception($e);
                    }
                    $params = array_merge($backend['params'], ['db' => $db]);
                    break;

                case 'Spamd_Ldap':
                    $params = array_merge(
                        ['binddn' => sprintf(
                            '%s=%s,%s',
                            $backend['params']['uid'],
                            $user,
                            $backend['params']['basedn']
                        ),
                            'bindpw' => $GLOBALS['registry']->getAuthCredential('password')],
                        $backend['params']
                    );
                    try {
                        $ldap = $injector->getInstance('Horde_Core_Factory_Ldap')
                            ->create('sam', $params);
                    } catch (Horde_Exception $e) {
                        throw new Sam_Exception($e);
                    }
                    $params = array_merge($backend['params'], ['ldap' => $ldap]);
                    break;

                case 'Spamd_Ftp':
                    $params = array_merge(
                        ['username' => $user,
                            'password' => $GLOBALS['registry']->getAuthCredential('password')],
                        $backend['params']
                    );
                    try {
                        $vfs = $injector->getInstance('Horde_Core_Factory_Vfs')
                            ->create(
                                'sam',
                                ['type' => 'ftp',
                                    'params' => $params]
                            );
                    } catch (Horde_Exception $e) {
                        throw new Sam_Exception($e);
                    }
                    $params = array_merge($backend['params'], ['vfs' => $vfs]);
                    break;

                default:
                    $params = $backend['params'];
                    break;
            }

            $class = 'Sam_Driver_' . $backend['driver'];
            $this->_instances[$signature] = new $class($user, $params);
        }

        return $this->_instances[$signature];
    }
}
