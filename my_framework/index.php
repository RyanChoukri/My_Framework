<?php
define('TOKEN', sha1('TokenMVCdeQualitéPourLesVaraibleDeSession'));
define('BASE_URI', substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])));
session_start();
require_once(implode(DIRECTORY_SEPARATOR, ['application', 'autoload.php']));
$app = new MyFramework\Core();

$app->run();
?>