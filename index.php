<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim();
$app = new \Slim\Slim(array(
    'templates.path' => './templates',
    'debug' => true,
    'mode' => 'development'
));

$app->get('/', function () use ($app) {
    $var = $app->view()->fetch('my_template.php', array('key' => 'sdfsdf'));
    $app->render('main.php', array('bla' => $var));
});
$app->run();
