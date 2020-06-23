<?php

use Alek\Models\Pixel;
use Psr\Container\ContainerInterface;

class ApiController
{

    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function createPixel($request, $response, $args) {
		/* SAVE IN DATABASE start*/
	    $pixel = new Pixel($request->getAttribute('pixelType'), $request->getAttribute('userId'), $request->getAttribute('occuredOn'), $request->getAttribute('portalId'));

	    $db = $this->container->get('db');
		$result = $db->store_pixel($pixel);

	    if($result == 401){
	        $response->getBody()->write('An existing item already exists');
	        return $response->withStatus(401);
	    }
	    if($result == 500){
	        $response->getBody()->write("Unknown error occured");
	        return $response->withStatus(500);
	    }
		/* SAVE IN DATABASE end*/

		$response->getBody()->write('Successfully saved');
	    return $response->withStatus(201);	
    }
}
