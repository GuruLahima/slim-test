<?php
namespace Alek\Storage;
require_once __DIR__ . '/../interfaces/StorageEngine.php';
require_once __DIR__ . '/../models/Pixel.php';

use Alek\Models\Pixel as Pixel;

class MYSQLI_engine implements StorageEngine
{
	public $mysqli;
    public function __construct($host, $dbname, $user, $pass){
        $mysqli = new mysqli($host, $user, $pass, $dbname);
    }
    

    public function store_pixel(Pixel $pixel){

        $query = "SELECT * FROM pixels WHERE pixel_type = '".$pixel->pixelType."' AND user_id = $userId AND occured_on = $occuredOn AND portal_id = $portalId";
        $result = $mysqli->query($query);
        if($result){
	        return 401;
    	}

        $query = "INSERT INTO  pixels (pixel_type, user_id, occured_on, portal_id) VALUES ('".$pixel->pixelType."', $pixel->userId, $pixel->occuredOn, $pixel->portalId)";
        $result = $mysqli->query($query);
        if(!$result){
	        return 500;
        }
    }

}



	
