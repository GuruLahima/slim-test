<?php
namespace Alek\Models{
    
    class Pixel{
        
        public $pixelType;
        public $occuredOn;
        public $userId;
        public $portalId;   

        public function __construct($pixelType, $userId, $occuredOn, $portalId) {
            $this->pixelType = $pixelType;
            $this->userId = $userId;
            $this->occuredOn = $occuredOn;
            $this->portalId = $portalId;
        }    

        public function values(){
        	return [$this->pixelType, $this->userId, $this->occuredOn, $this->portalId];
        }
    }
}