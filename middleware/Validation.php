<?php

namespace Alek\Validate;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use \Slim\Psr7\Response;

class Validation{

	public $middleware;

	public function __construct(){
		$this->middleware = function (Request $request,  RequestHandler $handler) {
			$response = new Response();

			$contentType = $request->getHeaderLine('Content-Type');
		    if (strstr($contentType, 'application/json')) {

		    	$contentType = $request->getHeaderLine('Content-Type');

		        if (strstr($contentType, 'application/json')) {
		            $contents = json_decode(file_get_contents('php://input'), true);
		            if (json_last_error() === JSON_ERROR_NONE) {
		                $request = $request->withParsedBody($contents);
		            }
		        }
		        else{
		        	$response->getBody()->write('Invalid request');
					return $response->withStatus(400);
		        }
		    }

			// validation of request
			$content = $request->getParsedBody();
			if($content == null){
				$response->getBody()->write('Invalid request');
				return $response->withStatus(400);
			}
			if(!isset($content['pixelType']) || !is_string($content['pixelType']) || ($content['pixelType'] != "SOI" && $content['pixelType'] != "DOI") ){
				$response->getBody()->write('Invalid input');
				return $response->withStatus(400);
			}
			$pixelType = $content['pixelType'];
			if(!isset($content['userId']) || !is_int($content['userId'])){
				$response->getBody()->write('Invalid input');
				return $response->withStatus(400);
			}
			$userId = $content['userId'];
			if(!isset($content['occuredOn']) || !is_int($content['occuredOn'])){
				$response->getBody()->write('Invalid input');
				return $response->withStatus(400);
			}
			$occuredOn = $content['occuredOn'];
			if(!isset($content['portalId']) || !is_int($content['portalId'])){
				$response->getBody()->write('Invalid input');
				return $response->withStatus(400);
			}
			$portalId = $content['portalId'];

			$request = $request->withAttribute('pixelType', $pixelType);
			$request = $request->withAttribute('userId', $userId);
			$request = $request->withAttribute('occuredOn', $occuredOn);
			$request = $request->withAttribute('portalId', $portalId);

			$response = $handler->handle($request);
			return $response;
		};
	}

}