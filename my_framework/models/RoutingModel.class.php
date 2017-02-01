<?php
namespace MyFramework;
use \PDO;

class RoutingModel extends Core
{
    public function getAll()
    {
        $orm = new ORM();
        return $orm->find('routing');
    }

    public function add($entry = null)
    {
        if(empty($entry)) {
            return false;
        }
        $orm = new ORM();
        return $orm->add('routing', $entry);

    }

    public function remove($entry = null)
    {
        if(empty($entry)) {
            return false;
        }
        $orm = new ORM();
        return $orm->remove('routing', $entry);
    }

    public function replace($entry = null)
    {
        if(empty($entry)) {
            return false;
        }
        $orm = new ORM();
        return $orm->update('routing', array_reverse($entry));
    }

    public function getTable()
    {
        try {
            $sql = "SHOW COLUMNS FROM routing";
            $req = self::$_pdo->prepare($sql);
            $req->execute();
            $column_array = $req->fetchAll(PDO::FETCH_ASSOC);
            if(empty($column_array)) {
                echo "Table inexistante";
                exit;
            }
            return $column_array;
        }
        catch (Exception $e) {
            return;
        }
    }
}
?>