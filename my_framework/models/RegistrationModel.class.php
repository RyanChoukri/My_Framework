<?php
namespace MyFramework;
use \PDO;

class RegistrationModel extends Core
{
    public function getAll()
    {
        $orm = new ORM();
        return $orm->find('users');
    }

    public function find_user($user = null, $state = null)
    {
        if(empty($user)) {
            return false;
        }
        $orm = new ORM();
        if($state != null) {
            $salt = "LeCloudc'estCommeLesErreurPHP".
        "MoinsTuEnAsMieuxTuTePorte";
            $user['password'] = hash('ripemd160', $user['password'] . $salt);
            $user = $orm->find('users', ['where' => ['login' => $user['login'],
                'password' => $user['password']]]);
            return (!empty($user)) ? $user : false;
        }
        return (empty($orm->find('users', ['where' => ['login' => $user]]))) ?
        true : false;
    }

    public function add($entry = null)
    {
        if(empty($entry)) {
            return false;
        }
        $orm = new ORM();
        return $orm->add('users', $entry);

    }
}
?>