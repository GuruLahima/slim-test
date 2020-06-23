<?php
use DI\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;
use \Firebase\JWT\JWT;
use \Slim\Psr7\Response;

require __DIR__ . '/../vendor/autoload.php';

// custom dependencies
require_once("../config/config.php");
require __DIR__ . '/../db/pdo_connection.php';
require __DIR__ . '/../db/mysqli_connection.php';
require __DIR__ . '/../middleware/Authenticate.php';
require __DIR__ . '/../middleware/Validation.php';
require __DIR__ . '/../controllers/ApiController.php';
use Alek\Storage\PDO_engine;
use Alek\Storage\MYSQLI_engine;
use Alek\Models\Pixel;
use Alek\Auth\Auth;
use Alek\Validate\Validation;


// Create Container using PHP-DI
$container = new Container();
// Set container to create App with on AppFactory
AppFactory::setContainer($container);
// Instantiate app
$app = AppFactory::create();
// Add Error Handling Middleware
$app->addErrorMiddleware(true, false, false);
$container = $app->getContainer();

// register the storage engine as a dependency
$container->set('db', function () use ($config){
    if($config['storage_engine'] == "pdo"){
        return new PDO_engine($config['pdo_provider'], $config['host'], $config['dbname'], $config['user'], $config['pass']);
    }
    else{
        return new MYSQLI_engine($config['host'], $config['dbname'], $config['user'], $config['pass']);
    }
});



// get the middleware
$auth = new Auth($config['secret_key']);
$validation = new Validation();


// Add route callbacks

/* helper route for generating a token to be used in the post route */
$app->get('/generate-token', function (Request $request, Response $response, array $args) use ($config) {
	$full_name = $request->getQueryParams()["full_name"];
    $email = $request->getQueryParams()["email"];

    $payload = Auth::generateToken($full_name, $email, $config['secret_key']);
    $response->getBody()->write($payload);

    return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
});



 /* main route */
$app->post('/pixel', 'ApiController:createPixel')->add([$auth, 'closure'])->add($validation->middleware); // register route middleware


// Run application
$app->run();
