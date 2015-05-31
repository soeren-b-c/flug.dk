    <?php
require 'vendor/autoload.php';
require 'libs/Calendar.php';
require 'libs/Blog.php';

$app = new \Slim\Slim();
$app = new \Slim\Slim(array(
    'templates.path' => './templates',
    'debug' => true,
    'mode' => 'development'
));
$app->contentType('text/html; charset=utf-8');

$app->get('/', function () use ($app) {
    $header = $app->view()->fetch('header.php', array());
    $footer = $app->view()->fetch('footer.php', array());
    $app->render('main.php', array('header' => $header, 'footer' => $footer));
});

$app->get('/blog', function () use ($app) {
    $blog = new \Libs\Blog();
    $header = $app->view()->fetch('header.php', array());
    $footer = $app->view()->fetch('footer.php', array());
    $app->render('blog.php', array('header' => $header, 'footer' => $footer, 'blog' => $blog));
});

$app->get('/calendar/:timeMin', function ($timeMin) use ($app) {
    $app->contentType('application/json; charset=utf-8');
    $calendar = new \Libs\Calendar();
    $limit = $paramValue = $app->request->get('limit');
    $nextEvent = $calendar->getCalendar($timeMin, $limit);
    echo $nextEvent;
});

$app->get('/calendar', function () use ($app) {
    $app->contentType('application/json; charset=utf-8');
    $calendar = new \Libs\Calendar();
    $limit = $paramValue = $app->request->get('limit');
    $nextEvent = $calendar->getFutureEvents($limit);
    echo $nextEvent;
});

$app->run();
