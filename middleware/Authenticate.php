<?php 
namespace Alek\Auth;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use \Slim\Psr7\Response;
use \Firebase\JWT\JWT;
require_once __DIR__ . '/../interfaces/AuthInterface.php';

class Auth implements AuthContract{

    // public $middleware;
    public $secret_key;

    public function __construct($secret_key){
        $this->secret_key = $secret_key;
    }

    public function closure(Request $request, RequestHandler $handler){
        $response = new Response();

        $jwt = $request->getHeaderLine('Authorization');
        $jwt = str_replace("Bearer ", "", $jwt);
        try {

            $decoded = JWT::decode($jwt, $this->secret_key, array('HS256'));

            // Access is granted. continue with the request

        }catch (\Exception $e){

            $payload = json_encode(array(
                "message" => "Access denied.",
                "error" => $e->getMessage()
            ));
            $response->getBody()->write($payload);
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $response = $handler->handle($request);
        return $response;
    }

    public static function generateToken ($full_name, $email, $secret_key){


        $issuer_claim = "THE_ISSUER"; // this can be the servername
        $audience_claim = "THE_AUDIENCE";
        $issuedat_claim = time(); // issued at
        $notbefore_claim = $issuedat_claim; //not before in seconds
        $expire_claim = $issuedat_claim + 3600; // expire time in seconds
        $token = array(
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "data" => array(
                "full_name" => $full_name,
                "email" => $email
        ));

        $jwt = JWT::encode($token, $secret_key);
        $payload = json_encode(
            array(
                "message" => "Successful login.",
                "jwt" => $jwt,
                "email" => $email,
                "expireAt" => $expire_claim
            ));

        return $payload;
    }
}
