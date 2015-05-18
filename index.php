    <?php
require 'vendor/autoload.php';
require 'libs/Calendar.php';

$app = new \Slim\Slim();
$app = new \Slim\Slim(array(
    'templates.path' => './templates',
    'debug' => true,
    'mode' => 'development'
));
$app->contentType('text/html; charset=utf-8');

$app->get('/', function () use ($app) {
    $var = $app->view()->fetch('my_template.php', array('key' => 'sdfsdf'));
    $app->render('main.php', array());
});

$app->get('/calendar/:timeMin', function ($timeMin) use ($app) {
    $app->contentType('application/json; charset=utf-8');
    $calendar = new \Libs\Calendar();
    $limit = $paramValue = $app->request->get('limit');
    $nextEvent = $calendar->getCalendar($timeMin, $limit);
    echo $nextEvent;
});

$app->get('/calendar/', function () use ($app) {
    $app->contentType('application/json; charset=utf-8');
    $calendar = new \Libs\Calendar();
    $limit = $paramValue = $app->request->get('limit');
    $nextEvent = $calendar->getFutureEvents($limit);
    echo $nextEvent;
});

$app->run();
