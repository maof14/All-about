<?php
// project
use Mattias\FormCollection\CFormCollection as FC;
require __DIR__.'/config.php'; 

$di  = new \Anax\DI\CDIFactoryDefault();
        
$di->setShared('db', function() {
    $db = new \Anax\Database\CDatabaseBasic();
    $db->setOptions(require ANAX_APP_PATH . 'config/database_sqlite.php');
    $db->connect();
    return $db;
});

$di->set('UsersController', function() use ($di){
    $controller = new \Anax\Users\UsersController();
    $controller->setDI($di);
    return $controller;
});
$di->set('QuestionsController', function() use ($di){
    $controller = new \Anax\Questions\QuestionsController();
    $controller->setDI($di);
    return $controller;
});
$di->set('AnswersController', function() use ($di){
    $controller = new \Anax\Answers\AnswersController();
    $controller->setDI($di);
    return $controller;
});
$di->set('TagsController', function() use ($di){
    $controller = new \Anax\Tags\TagsController();
    $controller->setDI($di);
    return $controller; 
});

$di->set('form', 'Mos\HTMLForm\CForm');
$di->set('cf', 'Mattias\FormCollection\CFormCollection');

$app = new \Anax\MVC\CApplicationBasic($di);
$app->theme->configure(ANAX_APP_PATH . 'config/theme.php');
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar.php');
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
$app->theme->addStylesheet('css/font-awesome-4.2.0/css/font-awesome.min.css');

$app->router->add('', function() use ($app) {
    $app->theme->setTitle("Start");
 
    $content = $app->fileContent->get('index.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
 
    $app->views->add('project/article-front', [
        'content' => $content,
    ]);

    $app->dispatcher->forward([
        'controller' => 'tags',
        'action' => 'list'
    ]); 

    $app->dispatcher->forward([
        'controller' => 'questions',
        'action' => 'frontpage'
    ]);

});

$app->router->add('about', function() use ($app) {
    $app->theme->setTitle('Om oss');

    $content = $app->fileContent->get('about.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');

    $app->views->add('project/article', [
        'content' => $content
    ]);
});

$app->router->handle();
$app->theme->render();