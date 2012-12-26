<?php

class Vimesso_Helper_Api extends Mage_Core_Helper_Abstract {

    const XML_PATH_VIMESSO_API_KEY    = 'vimesso_options/settings/api_key';
    const XML_PATH_VIMESSO_API_SECRET = 'vimesso_options/settings/api_secret';
    const XML_PATH_VIMESSO_API_URL    = 'vimesso_options/settings/api_url';
    const XML_PATH_VIMESSO_SEND_EMAIL    = 'vimesso_options/email_settings/enable_vimesso_email';

    public function getApiKey() {
        return Mage::getStoreConfig(self::XML_PATH_VIMESSO_API_KEY);
    }

    public function getApiSecret() {
        return Mage::getStoreConfig(self::XML_PATH_VIMESSO_API_SECRET);
    }

    public function getApiUrl() {
        //return Mage::getStoreConfig(self::XML_PATH_VIMESSO_API_URL);
        return 'http://api.vimesso.com';
    }
   
    public function getSendEmailOption() {
	return Mage::getStoreConfig(self::XML_PATH_VIMESSO_SEND_EMAIL);
    }
 
    public function getVimessoModel()
    {
        return Mage::getModel('vimesso/vimesso');
    }

    public function pingVimesso()
    {
        $httpClient = new Zend_Http_Client($this->getApiUrl());
        $response = false;
        try {
            $response = $httpClient->request();
        } catch (Exception $e) {}

        if($response /*&& $response->isSuccessful() === true*/) { // its commented because http://test.vimesso.fr returns 404
            return true;
        } else {
            return false;
        }
    }

    public function registerVimesso($onum, $email)
    {
        $httpClient = new Zend_Http_Client($this->getApiUrl() . '/api/post');
        $httpClient->setHeaders('apikey', $this->getApiKey());
        $httpClient->setHeaders('apisecret', $this->getApiSecret());
        $httpClient->setParameterPost('onum', $onum);
        $httpClient->setParameterPost('email', $email);
	$httpClient->setParameterPost('send_email', $this->getSendEmailOption()); 
	$response = $httpClient->request(Zend_Http_Client::POST);
        $xmlRaw = $response->getBody();
        $xml = simplexml_load_string($xmlRaw);
        $attributes = $xml->response->message->attributes();
        
        if (!isset($attributes['code'])) {
            Mage::throwException('Unexpected response from Vimesso.');
        }

        $vimesso = $this->getVimessoModel();
        
        foreach($attributes as $attribute => $value) {
            $vimesso->setData($attribute, (string)$value);
        }
        
        return $vimesso;
    }

    public function getVimessoByOnum($onum) {
        $httpClient = new Zend_Http_Client($this->getApiUrl() . '/api');
        $httpClient->setHeaders('apikey', $this->getApiKey());
        $httpClient->setHeaders('apisecret', $this->getApiSecret());
        $httpClient->setParameterGet('order_number', $onum);
        $response = $httpClient->request(Zend_Http_Client::GET);
        $xmlRaw = $response->getBody();
        $xml = simplexml_load_string($xmlRaw);

        $vimesso = $this->getVimessoModel();
        
        foreach ($xml->response->items->message as $item) {
            foreach($item->attributes() as $attribute => $value) {
                $vimesso->setData($attribute, (string)$value);
            }
            return $vimesso;
        }
        return $vimesso;
    }
    
    public function getVimessoCollectionByEmail($email) {
        $collection = new Varien_Data_Collection();
        $httpClient = new Zend_Http_Client($this->getApiUrl() . '/api');
        $httpClient->setHeaders('apikey', $this->getApiKey());
        $httpClient->setHeaders('apisecret', $this->getApiSecret());
        $httpClient->setParameterGet('customer', $email);
        $response = $httpClient->request(Zend_Http_Client::GET);
        $xmlRaw = $response->getBody();
        $xml = simplexml_load_string($xmlRaw);

        foreach($xml->response->items->message as $item)
        {
            $vimesso = $this->getVimessoModel();
            foreach($item->attributes() as $attribute => $value)
            {
                $vimesso->setData($attribute, (string)$value);
            }
            $collection->addItem($vimesso);
        }
        return $collection;
    }
}
