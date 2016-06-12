<?php

namespace Deirde\BbcNewsWithVotes {

    class Feed {
        
        /**
         * @var string
         */
        var $title;
        
        /**
         * @var string
         */
        var $description;
        
        /**
         * @var string
         */
        var $link;
        
        /**
         * @var array
         */
        var $image = [
            'url',
            'title',
            'link'
        ];
        
        /**
         * @var string
         */
        var $generator;
        
        /**
         * @var string
         */
        var $lastBuildDate;
        
        /**
         * @var string
         */
        var $copyright;
        
        /**
         * @var string
         */
        var $language;
        
        /**
         * @var int
         */
        var $votes = 0;
        
        /**
         * Storage XML file name.
         * @var string
         */
        private $storageXmlFileName = 'data.xml';

        /**
         * The class constructor.
         * @param array $attrs
         */
        public function __construct(array $attrs) {
            
            foreach ($attrs as $key => $val) {
                
                if (is_object($val)) {
                    $val = $val->__toString();
                }
                
                foreach($this as $k => $v) {
                    if (isset($attrs[$k])) {
                        $this->$k = $attrs[$k];
                    }
                }
                
            }
                
            $this->getVotes($this->link);
            
        }

        /**
         * @return object(DOMDocument)
         */
        private function getStorageRes() {
            
            $response = new \DOMDocument();
            @$response->load(__DIR__ . DIRECTORY_SEPARATOR . $this->storageXmlFileName);
            return $response;
            
        }

        /**
         * Reads the storage file and assigns the item votes to the item property.
         * @param string $link
         * @return bool(true)
         */
        private function getVotes($link) {
            
            $storageRes = $this->getStorageRes();
            $dxp = new \DOMXPath($storageRes);
            $id = urlencode($link); 
            false && $node = new \DOMElement();
            
            $query = $dxp->query("//item[@link='". $id ."']/*");
            
            foreach($query as $node){
                $key = $node->nodeName;
                $val = $node->nodeValue;
                $attrs[$key] = $val;
            }
            
            if (isset($attrs['votes'])) {
                $this->votes = $attrs['votes'];
            }
            
            return true;
            
        }
        
    }
    
}

?>