<?php

namespace Deirde\BbcNewsWithLikes {

    class Feed {
        
        var $title;
        var $description;
        var $link;
        var $image = [
            'url',
            'title',
            'link'
        ];
        var $generator;
        var $lastBuildDate;
        var $copyright;
        var $language;
        var $ttl;
        var $likes = 0;
        
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
                
            $this->getLikes($this->link);
            
        }
        
        private function getStorageRes() {
            
            $response = new \DOMDocument();
            @$response->load(__DIR__ . DIRECTORY_SEPARATOR . 'data.xml');
            return $response;
            
        }
        
        private function getLikes($link) {
            
            $storageRes = $this->getStorageRes();
            $dxp = new \DOMXPath($storageRes);
            $id = urlencode($link); 
            false && $node = new \DOMElement();
            
            $query = $dxp->query("//item[@url='". $id ."']/*");
            
            foreach($query as $node){
                $key = $node->nodeName;
                $val = $node->nodeValue;
                $attrs[$key] = $val;
            }
            
            if (isset($attrs['likes'])) {
                $this->likes = $attrs['likes'];
            }
            
        }
        
    }
    
}

?>