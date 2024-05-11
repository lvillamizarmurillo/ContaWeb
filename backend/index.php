<?php
namespace NexusRouter;

// require "nexus-router/src/NexusLoader.php";
require "vendor/autoload.php";

// NexusRouter\Router::get('/users' , 'Controller/User');
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'Config/config.php';//configuraciones de la app como de la bd.

new NexusLoader();