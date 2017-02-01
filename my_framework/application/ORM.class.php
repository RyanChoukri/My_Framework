<?php
namespace Myframework;
use \PDO;

class ORM extends Core
{
    public function add($table = null, $content = null)
    {
        $table_list = "";
        $index_list = "";
        if(empty($table)||empty($content)) {
            return;
        };
        if(trim($table) == "" && !is_array($content)) {
            return;
        }
        $sql = "INSERT INTO $table (";
        foreach ($content as $k => $v) {
            $table_list .= "$k,";
            $index_list .= ":$k,";
        }
        $sql .= substr($table_list, 0, -1) . ") VALUE(";
        $sql .= substr($index_list, 0, -1) . ")";
        try {
            $req = self::$_pdo->prepare($sql);
            $req->execute($content);
            $insert = self::$_pdo->lastInsertId();
            if($insert === "0") {
                return;
            }
            return true;
        }
        catch (Exception $e) {
            return;
        }
    }

    public function update($table = null, $content = null)
    {
        $table_list = "";
        if(empty($table) || empty($content) || empty(current($content))) {
            return;
        };
        if(trim($table) == "" && !is_array($content)
            && !is_numeric(current($content))) {
                return;
        }
        reset($content);
        $first_key = key($content);
        $sql = "UPDATE $table SET";

        foreach ($content as $k => $v) {
            if($k != $first_key) {
                $table_list .= " $k = :$k,";
            }
        }
        $id = ":" . $first_key;
        $sql .= substr($table_list, 0, -1);
        $sql .= " WHERE " . $first_key . " = $id";
        try {
            $req = self::$_pdo->prepare($sql);
            $req->execute($content);
            /*var_dump($req->errorInfo());*/
            $insert = $req->rowCount();
            if($insert == "0") {
                return false;
            }
            return true;
        }
        catch (Exception $e) {
            return;
        }
    }

    public function remove($table = null, $content = null)
    {
        if(empty($table) || empty($content) || empty(current($content))) {
            return;
        };
        if(trim($table) == "" && !is_array($content)
            && !is_numeric(current($content))) {
                return;
        }
        $id = ":" . key($content);
        $sql = "DELETE FROM $table
        WHERE " . key($content) . " = $id";
        try {
            $req = self::$_pdo->prepare($sql);
            $req->execute($content);
            $insert = $req->rowCount();
            if($insert == "0") {
                return false;
            }
            return true;
        }
        catch (Exception $e) {
            return;
        }
    }

    public function find($table = null, $content = null)
    {
       $value_list = [];
       if(empty($table)) {
            return;
        };
        if(empty($content)) {
            $sql = "SELECT * FROM $table";
            $req = self::$_pdo->prepare($sql);
            $req->execute();
            return $req->fetchAll(PDO::FETCH_ASSOC);
        };
        if(trim($table) == "" && !is_array($content)) {
            return;
        }
        $sql = "SELECT * FROM $table";

        if(array_key_exists('select', $content)){
            $sql = "SELECT ";
            $sql .= implode(", ",$content['select']);
            $sql .= " FROM $table";
        }

        if(array_key_exists('inner', $content)){
            $in = "";
            $on = "";
            $status = true;
            foreach ($content['inner'] as $t) {
                $sql  .= " LEFT OUTER JOIN $t ";
                $sql .= "ON " . $t . ".id_"  . $t . " = " .
                $table . ".id_"  . $t;
            }
        }

        if(!empty($content['where'])) {
            $where_list = " WHERE";
            foreach ($content['where'] as $k => $v) {
                $where_list .= " $k = '" . $v . "' AND";
                $value_list[$k] = $v;
            }
            $sql .= substr($where_list, 0, -4);
        }
        if(!empty($content['order_by'])) {
            $order_list = " ORDER BY";
            foreach ($content['order_by'] as $v) {
                $order_list .= " $v,";
            }
            $sql .= substr($order_list, 0, -1);
        }
        if(!empty($content['limit'])) {
            $limit_list = " LIMIT";
            $count = 0;
            foreach ($content['limit'] as $v) {
                $count++;
                if($count <= 2) {
                    $limit_list .= " $v,";
                }
            }
            $sql .= substr($limit_list, 0, -1);
        }
        $req = self::$_pdo->prepare($sql);
        $req->execute($value_list);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>