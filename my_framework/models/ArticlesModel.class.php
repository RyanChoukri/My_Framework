<?php
namespace MyFramework;
use \PDO;

class ArticlesModel extends Core
{
    public function getAll()
    {
        $orm = new ORM();
        return $orm->find('articles');
    }

    public function add($entry = null)
    {
        if(empty($entry)) {
            return false;
        }
        $orm = new ORM();
        return $orm->add('articles', $entry);

    }

    public function remove($entry = null)
    {
        if(empty($entry)) {
            return false;
        }
        $orm = new ORM();
        return $orm->remove('articles', $entry);
    }

    public function replace($entry = null)
    {
        if(empty($entry)) {
            return false;
        }
        $orm = new ORM();
        return $orm->update('articles', array_reverse($entry));
    }

    public function getTable()
    {
        try {
            $sql = "SHOW COLUMNS FROM articles";
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