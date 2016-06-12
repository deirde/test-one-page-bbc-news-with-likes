<?php

/**
 * BBC news with votes
 */
namespace Deirde\BbcNewsWithVotes
{
    
    /**
     * Opens the session.
     */
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    class Votes
    {
        
        /**
         * The request key name.
         * @var string
         */
        private $_ = 'votes';
        
        /**
         * The name of the storage XML file.
         * @var string
         */
        private $storageXmlFileName = 'data.xml';
        
        /**
         * The class constructor. It triggers the save action if the request is correct.
         * @param array $request
         */
        public function __construct(array $request)
        {
            
            // XHR request.
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
                && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
                && isset($_POST['action'])
                && $_POST['action'] == 'xhr'
                && isset($_POST['data'])) {
                $this->xhrGet($_POST['data']);
                die();
            }
            
            // If requests it triggers the save.
            if (!empty($request)
                && isset($request[$this->_])) {
                $request = array_keys($request[$this->_]);
                
                // Triggers the save method.
                $this->save($request);
                
                // No new post on refresh.
                $_SESSION['flash'] = true; // The flash message.
                die(header('location: ' . $_SERVER['REQUEST_URI']));
                
            }
            
        }
        
        /**
         * Gets and cleans the xhr data.
         * @param array $_data
         */
        private function xhrGet($_data) {
            
            if ($_data) {
                parse_str($_data, $data);
                
                $data = array_keys($data[$this->_]);
                $this->save($data); // Triggers the save method.
                $this->xhrResponse($data); // Triggers the XHR response.
            }
            
            die();
            
        }
        
        /**
         * The XHR response.
         * @param array $data
         */
        private function xhrResponse($data) {
            
            $response = [];
            foreach($data as $key => $val) {
                $response[] = $val;
            }
            
            die(json_encode($response));
            
        }
        
        /**
         * Saves the contents on the storage XML file.
         * @param array $data
         */
        private function save($data)
        {
            
            // Retrieves the content from the <set> method.
            $data = $this->set($data);
            
            // XML storage file header.
            $contents = '<?xml version="1.0" encoding="UTF-8"?>';
            $contents .= '<items>';
            
            // Cycles the items.
            foreach ($data as $item) {
                
                $contents .= '<item link="' . $item['link'] . '">';
                $contents .= '<votes>' . $item['votes'] . '</votes>'; 
                $contents .= '</item>'; 
                
            }
            
            // XML storage file footer.
            $contents .= '</items>';
            
            // Writes the new storage XML file.
            file_put_contents($this->storageXmlFileName, $contents);
            
        }
        
        /**
         * Sets the content to save on the storage XML file.
         * @param array $data
         */
        private function set($data)
        {
            
            $items = (array)simplexml_load_string(
                file_get_contents($this->storageXmlFileName),
                'SimpleXMLElement',
                LIBXML_NOCDATA);
            
            // Gets the items from the request.
            $ext_items = array();
            foreach ($data as $_item) {
                $ext_items[] = array(
                    'link' => urlencode($_item),
                    'votes' => 1
                );
            }
            
            // If the XML exists and it contains at least one item.
            if (is_array($items)
                && isset($items['item'])) {
                    
                $int_items = array();
               
                // Gets the items from the storage XML file.
                foreach ($items['item'] as $item) {
                    
                    $item = (array) $item;
                    $link = reset($item);
                    $link = $link['link'];
                    $votes = $item['votes'];
                    
                    $pos = array_search(urldecode($link), $data);
                    if ($pos !== false) {
                        unset($ext_items[$pos]);
                        $votes++;
                    }
                        
                    $int_items[] = array(
                        'link' => $link,
                        'votes' => (int)$votes
                    );
                    
                }
                
            }
            
            // Merges the items.
            $response = array_merge_recursive($int_items, $ext_items);
            
            return $response;
            
        }
        
    }
    
}

?>