<?php

/**
 * BBC news with likes
 */
namespace Deirde\BbcNewsWithLikes
{
    
    require_once('Feed.class.php');

    class Results
    {

        /**
         * Feed URL.
         * @var string
         */
        private $url;

        /**
         * Feed channel info.
         * @var array
         */
        public $channel = [];

        /**
         * Feeds.
         * @var array
         */
        private $item = [];

        /**
         * Page title.
         * @var string
         */
        private $pageTitle;

        /**
         * @param $url
         */
        public function __construct($url)
        {
            
            $this->setPageTitle();
            $this->url = $url;
            $this->parseFeedUrl();
            
            if (isset($_POST['likes'])) {
                $this->getLikes($_POST['likes']);
            }
            
            if (isset($_POST['action']) 
                && $_POST['action'] == 'xhrGetLikes'
                && isset($_POST['data'])) {
                $this->xhrGetLikes($_POST['data']);
            }
            
        }
    
        /**
         * Gets the page title.
         * @return string
         */
        public function getPageTitle() {
            
            return $this->pageTitle;
            
        }
    
        /**
         * Sets the page title.
         */
        private function setPageTitle()
        {
    
            $this->pageTitle = get_class($this);
    
        }

        /**
         * Sets the channel attributes and values.
         * @param $xml
         */
        private function setFeedChannelAttrs($xml)
        {
            
            $channel = (array)$xml->channel;
            unset($channel['item']);
            
            foreach ($channel as $key => $val) {
                $this->channel[$key] = $xml->channel->{$key}->__toString();
            }
            
        }

        /**
         * The URL parser.
         */
        public function parseFeedUrl()
        {
            
            $xml = @simplexml_load_file($this->url);
            
            if ($xml) {
                
                $this->setFeedChannelAttrs($xml);
                foreach ($xml->channel->item as $item) {
                    $this->items[] = New Feed((array)$item);
                }
            
            } else {
                
                trigger_error(_('The URL provided is not valid.'), E_USER_ERROR);
                
            }
            
        }

        /**
         * Finds a single feed item by id.
         * @param $link
         * @return bool
         */
        private function findItemById($link)
        {
            
            $response = false;
            foreach ($this->items as $item) {
                if ($item->link == $link) {
                    $response = $item;
                }
            }
            return $response;
            
        }

        /**
         * Gets all the posted likes.
         * @param $likes
         */
        private function getLikes($likes)
        {
                
            foreach($likes as $key => $val) {
                
                $item = $this->findItemById($key);
                $item->likes++;
                
            }
            
            $this->setLikes();
            
        }

        /**
         * Gets all the ajax posted likes.
         * @param null $_data
         */
        private function xhrGetLikes($_data = null) {
            
            if ($_data) {
                parse_str($_data, $data);
                $this->getLikes($data['likes']);
                $this->xhrReturnLikes($data['likes']);
            }
            
            exit();
            
        }

        /**
         * Returns all the likes.
         * @param $likes
         */
        private function xhrReturnLikes($likes) {
            
            $response = [];
            foreach($likes as $key => $val) {
                $item = $this->findItemById($key);
                $response[$key] = $item->likes;
            }
            
            exit(json_encode($response));
            
        }

        /**
         * Sets the likes regenerating the file storage.
         */
        private function setLikes()
        {
            
            $contents = '<?xml version="1.0" encoding="UTF-8"?>';
            $contents .= '<items>';
            
            foreach ($this->items as $item) {
                
                if ($item->likes > 0) {
                    $contents .= '<item url="' . urlencode($item->link) . '">';
                    $contents .= '<likes>' . $item->likes . '</likes>'; 
                    $contents .= '</item>'; 
                }
                
            }
            
            $contents .= '</items>';
            file_put_contents('data.xml', $contents);
            
        }
    
    }

}

?>