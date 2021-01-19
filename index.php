 <?php
 use Psr\Http\Message\ResponseInterface as Response;
 use Psr\Http\Message\ServerRequestInterface as Request;
 use Slim\Factory\AppFactory;
 use \Source\Page;
 use \Source\api\Api;

  require __DIR__ . "/vendor/autoload.php";

 $app = AppFactory::create();
 // Add Routing Middleware
 $app->addRoutingMiddleware();

 $errorMiddleware = $app->addErrorMiddleware(true, true, true);

 $app->get('/',function (Request $request, Response $response, $args){
  $api = new Api();
  $page = new Page([
      "header"=>false,
      "footer"=>false
  ]);

   $page->setTpl('index');

  return $response;
 });



 $app->get('/api',function (Request $request, Response $response,$args){

  $api = new Api();
  $page = new Page([
      "header"=>false,
      "footer"=>false
  ]);


 $api->trackBack();

  $page->setTpl('api');

  return $response;
 });

 $app->run();
?>


