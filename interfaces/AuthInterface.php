<?php
namespace Alek\Auth;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

interface AuthContract{

	// the closure we pass to the add() method to register middleware
	public function closure(Request $request, RequestHandler $handler);

}