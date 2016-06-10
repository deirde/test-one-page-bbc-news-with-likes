<?php

/**
 * BBC news with votes
 */
namespace Deirde\BbcNewsWithVotes
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
            
            if (isset($_POST['votes'])) {
                $this->getVotes($_POST['votes']);
            }
            
            if (isset($_POST['action']) 
                && $_POST['action'] == 'xhrGetVotes'
                && isset($_POST['data'])) {
                $this->xhrGetVotes($_POST['data']);
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
         * Gets all the posted votes.
         * @param $votes
         */
        private function getVotes($votes)
        {
                
            foreach($votes as $key => $val) {
                
                $item = $this->findItemById($key);
                $item->votes++;
                
            }
            
            $this->setVotes();
            
        }

        /**
         * Gets all the ajax posted votes.
         * @param null $_data
         */
        private function xhrGetVotes($_data = null) {
            
            if ($_data) {
                parse_str($_data, $data);
                $this->getVotes($data['votes']);
                $this->xhrReturnVotes($data['votes']);
            }
            
            exit();
            
        }

        /**
         * Returns all the votes.
         * @param $votes
         */
        private function xhrReturnVotes($votes) {
            
            $response = [];
            foreach($votes as $key => $val) {
                $item = $this->findItemById($key);
                $response[$key] = $item->votes;
            }
            
            exit(json_encode($response));
            
        }

        /**
         * Sets the votes regenerating the file storage.
         */
        private function setVotes()
        {
            
            $contents = '<?xml version="1.0" encoding="UTF-8"?>';
            $contents .= '<items>';
            
            foreach ($this->items as $item) {
                
                if ($item->votes > 0) {
                    $contents .= '<item url="' . urlencode($item->link) . '">';
                    $contents .= '<votes>' . $item->votes . '</votes>'; 
                    $contents .= '</item>'; 
                }
                
            }
            
            $contents .= '</items>';
            file_put_contents('data.xml', $contents);
            
        }
    
    }

}

?>