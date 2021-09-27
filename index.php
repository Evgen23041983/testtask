<?php
require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();
// Please set to false in a production environment
$app['debug'] = false;

$task = array(
    '1'=> array(
        'name' => 'Task1',
        'description' => '...',
        'data_of_creation' => '10',
    ),
    '2' => array(
        'name' => 'Task2',
        'description' => '...',
        'data_of_creation' => '20',
    ),
);

//список всех садач
$app->get('/', function() use ($task) {

    return json_encode($task);
});

//получить задачу по ID и все ее теги
$app->get('/{id}', function (Silex\Application $app, $id) use ($task) {

    if (!isset($task[$id])) {
        $app->abort(404, "Id {$id} does not exist.");
    }

    return json_encode($task[$id]);
});

//вывести список всех тегов
$app->get('/tags/{id}', function (Silex\Application $app, $id) use ($task) {

    if (!isset($task[$id])) {
        $app->abort(404, "Id {$id} does not exist.");
    }
    $i=0;
    $tags = array();
    foreach (($task[$id]) as $key => $value) {
        $tags['tag' . $i] = $key;
        $i++;
    }

    return json_encode($tags);
});

//получить один тег по ID 
$app->get('/task/{id}/{tag_number}', function (Silex\Application $app, $id, $tag_number) use ($task) {

    if (!isset($task[$id])) {
        $app->abort(404, "ID {$id} does not exist.");
    }
    //var_dump($task[$id]);
    $i=0;
    $tags = array();
    foreach (($task[$id]) as $key => $value) {
        $tags[] = $key;
        $i++;
    }
    
    $tag['tag'] = $tags[$tag_number];
    return json_encode($tag);
});

//добавить новую задачу
$app->post('/', function (Silex\Application $app, Symfony\Component\HttpFoundation\Request $request) {

    $name = $request->get('name');
    $description = $request->get('description');
    $data_of_creation = $request->get('data_of_creation');
    
    $new_task = array(
        '3' => array(
            'name' => $name,
            'description'   => $description,
            'data_of_creation' => $data_of_creation,
            
        )
    );
    
    return new Symfony\Component\HttpFoundation\Response(json_encode($new_task), HTTP_CREATED);
});

//добавить новый тег
$app->post('/add', function (Silex\Application $app, Symfony\Component\HttpFoundation\Request $request) use ($task) {

    $name = $request->get('name');
    $description = $request->get('description');
    $data_of_creation = $request->get('data_of_creation');


    $i=0;
    foreach (($task) as $key => $value) {
       $toy = array(
        $key => array(
            'name' => $name,
            'description'   => $description,
            'data_of_creation' => $data_of_creation,
            
        )
    );
    }
    
    return new Symfony\Component\HttpFoundation\Response(json_encode($toy), HTTP_CREATED);
});


$app->run();