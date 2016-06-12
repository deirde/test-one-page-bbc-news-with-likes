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
         * The feed URL.
         * @var string
         */
        private $url;

        /**
         * The Feed channel info.
         * @var array
         */
        public $channel = [];

        /**
         * The feed items.
         * @var array
         */
        private $item = [];

        /**
         * @var string
         */
        public $pageTitle;

        /**
         * The class constructor.
         * @param string $url
         */
        public function __construct($url)
        {
            
            $this->setPageTitle();
            $this->url = $url;
            $this->parseFeedUrl();
            
        }
    
        /**
         * Gets the page title.
         * @return bool(true)
         */
        public function setPageTitle() {
            
            $this->pageTitle = get_class($this);
            
            return true;
            
        }

        /**
         * Sets the channel attributes and values.
         * @param object $xml(SimpleXMLElement)
         * @return boolean(true) 
         */
        private function setFeedChannelAttrs($xml)
        {
            
            $channel = (array)$xml->channel;
            unset($channel['item']);
            
            foreach ($channel as $key => $val) {
                $this->channel[$key] = $xml->channel->{$key}->__toString();
            }
            
            return true;
            
        }

        /**
         * The URL parser, it populates the item property.
         * @return boolean(true)|error(E_USER_ERROR)
         */
        public function parseFeedUrl()
        {
            
            $xml = @simplexml_load_file($this->url);
            
            if ($xml) {
                
                $this->setFeedChannelAttrs($xml);
                foreach ($xml->channel->item as $item) {
                    $this->items[] = New Feed((array)$item);
                }
                
                return true;
            
            } else {
                
                trigger_error(_('The URL provided is not valid.'), E_USER_ERROR);
                
            }
            
        }
    
    }

}

?>