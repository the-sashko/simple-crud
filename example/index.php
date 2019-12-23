<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__.'/crud/init.php';

use Plugins\SimpleCRUD\SimpleCRUDPlugin;

$xmlPath    = __DIR__.'/table.xml';
$configPath = __DIR__.'/config.json';
$formData   = $_POST;

$page = 1;

if (array_key_exists('page', $_GET)) {
    $page = (int) $_GET['page'];
}

$simpleCRUDPlugin = (new SimpleCRUDPlugin);
$simpleCRUDPlugin->init($xmlPath, $configPath, $formData, $page);

echo $simpleCRUDPlugin->execute();
