<?php
namespace Alek\Storage;
require_once __DIR__ . '/../interfaces/StorageEngine.php';
require_once __DIR__ . '/../models/Pixel.php';

use Alek\Models\Pixel as Pixel;
use PDO;

class PDO_engine implements StorageEngine
{
    public $pdo;

    public function __construct($driver, $host, $dbname, $user, $pass){
        // better load the settings with $container->get('settings')

        $charset = 'utf8';
        $collate = 'utf8_unicode_ci';
        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset COLLATE $collate"
        ];
        $this->pdo = new PDO($dsn, $user, $pass, $options);
    }
    

    public function store_pixel(Pixel $pixel){
        //var_dump the object so that we can see what its structure looks like.
        /*$response->getBody$pixelthStatus(201);*/

        //Prepare our INSERT SQL statement.
        $stmt = $this->pdo->prepare("SELECT * FROM pixels WHERE pixel_type = '".$pixel->pixelType."' AND user_id = $pixel->userId AND occured_on = $pixel->occuredOn AND portal_id = $pixel->portalId");
         
        //Execute the statement and insert our serialized object string.
        $stmt->execute($pixel->values());
         
        //Prepare our INSERT SQL statement.
        $stmt = $this->pdo->prepare("INSERT INTO pixels (pixel_type, user_id, occured_on, portal_id) VALUES (?, ?, ?, ?)");
         
        //Execute the statement and insert our serialized object string.
        $stmt->execute($pixel->values());
    }

}
