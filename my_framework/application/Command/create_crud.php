#!/usr/bin/env php
<?php

namespace MyFramework;
use \PDO;
if(empty($argv[1])) {
    echo "Parametre manquant(nom de la table SQL)";
    exit; 
}
if(!is_string($argv[1])) {
    echo "Le Parametre doit etre une chaînes " .
    "de caractère(nom de la tables SQL)";
    exit;
}

$s = DIRECTORY_SEPARATOR;
$repo = explode($s ,dirname(__FILE__));
array_pop($repo);
array_pop($repo);
$current_repo = $repo;
$core_file = str_replace(CHR(32),"",implode($s ,$repo) .
    "$s application $s Core.class.php");

include $core_file;

Class Create extends Core {
    public $_table;
    public $_dir;
    public $_s = DIRECTORY_SEPARATOR;
    public function __construct($dir, $table)
    {
        parent::__construct();
        $this->_dir = $dir;
        $this->_table = $table;
        $this->show_columns();
        $this->create_controlller();
        $this->create_model();
        $this->create_view();
        $this->create_routes();
        echo "Crud cree !!!";
    }

    public function show_columns() {
        $_column = [];
        $sql = "SHOW COLUMNS FROM $this->_table";
        $req = self::$_pdo->prepare($sql);
        $req->execute();
        $column_array = $req->fetchAll(PDO::FETCH_ASSOC);
        if(empty($column_array)) {
            echo "Table inexistante";
            exit;
        }
        foreach($column_array as $v) {
            array_push($_column, $v['Field']);
        }
    }

    public function create_controlller() {
        $k = "class_name";
        $path_patern = dirname(__FILE__) . $this->_s . "Assets" .
        $this->_s . "pattern_controller.txt";
        $new_control = $this->_dir . $this->_s . "controllers" .
        $this->_s . ucfirst($this->_table) . "Controller.class.php";
        if(!file_exists($path_patern)) {
            echo "fichier patern controllleur manquant";
            exit;
        }
        $file_patern = file_get_contents($path_patern);
        $patern = ['[{class_uc}]' => ucfirst($this->_table),
                    '[{class}]' => $this->_table];
        $file = strtr($file_patern, $patern);
        file_put_contents($new_control, $file);
    }

    public function create_model() {
        $k = "class_name";
        $path_patern = dirname(__FILE__) . $this->_s . "Assets" .
        $this->_s . "pattern_model.txt";
        $new_model = $this->_dir . $this->_s . "models" .
        $this->_s . ucfirst($this->_table) . "Model.class.php";
        if(!file_exists($path_patern)) {
            echo "fichier patern model manquant";
            exit;
        }
        $file_patern = file_get_contents($path_patern);
        $patern = ['[{class_uc}]' => ucfirst($this->_table),
                   '[{class}]'    => $this->_table];
        $file = strtr($file_patern, $patern);
        file_put_contents($new_model, $file);
    }

    public function create_view() {
        $view_list = ['default','add','get','replace','remove'];
        $path_view = $this->_dir . $this->_s .
        "views" . $this->_s . "$this->_table";

        $path_patern = dirname(__FILE__) . $this->_s . "Assets" .
        $this->_s;

        foreach($view_list as $method) {
            $view = $path_patern . "pattern_view_" . $method . ".txt";
            if(!file_exists($view)) {
                echo "fichier patern model manquant";
                exit;
            }
            $new_view = $path_view . $this->_s . $method . ".html";
            if(!is_dir($path_view)) {
                mkdir($path_view);
            }
            $file_patern = file_get_contents($view);
            $patern = ['[{class_uc}]' => ucfirst($this->_table),
                       '[{class}]'    => $this->_table];
            $file = strtr($file_patern, $patern);
            file_put_contents($new_view, $file);
        }
    }

    public function create_routes()
    {
        $sql = "INSERT INTO routing (url, real_path)
        VALUE ('" . $this->_table . "get', '" . $this->_table . "/get'),
        ('" . $this->_table . "add', '" . $this->_table . "/add'),
        ('" . $this->_table . "replace', '" . $this->_table . "/replace'),
        ('" . $this->_table . "remove', '" . $this->_table . "/remove')";
        $req = self::$_pdo->prepare($sql);
        $req->execute();
    }
}
$crud = new Create(implode($s, $current_repo), $argv[1]);
?>