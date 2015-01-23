<?php namespace Kameli\Quickpay\Api\Request;

use Kameli\Quickpay\Api\Response;
use Kameli\Quickpay\Configuration;
use Kameli\Quickpay\Exception;

abstract class Request
{
    use Configuration;

    /**
     * Quickpay API Version
     */
    const API_VERSION = 7;

    /**
     * Standard Quickpay API URL
     */
    const API_URL = 'https://secure.quickpay.dk/api';

    /**
     * Instance Quickpay API URL
     * @var string
     */
    protected $apiUrl;

    /**
     * The request data
     * @var array
     */
    protected $requestData;

    /**
     * The cURL instance
     * @var resource
     */
    protected $curl;

    /**
     * The MD5 string to ensure data integrity
     * @var null|string
     */
    protected $md5check;

    /**
     * Create a QuickPay API request
     * @param int|null     $quickpayId  Merchants ID at QuickPay (QuickPayID)
     * @param string|null  $md5check    MD5 check key. Is found/generated inside QuickPay Manager
     * @param string|null  $apiUrl      Alternate URL for QuickPay Payment API
     */
    public function __construct($quickpayId = null, $md5check = null, $apiUrl = null)
    {
        $this->requestData = $this->createInitialData($quickpayId ? $quickpayId : static::$defaultQuickpayId);
        $this->md5check = $md5check ? $md5check : static::$defaultMd5check;
        $this->apiUrl = $apiUrl ? $apiUrl : self::API_URL;

        if (static::$defaultApiKey) {
            $this->setApiKey(static::$defaultApiKey);
        }

        $this->setupCurl();
    }

    /**
     * Enable test mode
     * @return $this
     */
    public function enableTestmode()
    {
        return $this->setField('testmode', '1');
    }

    /**
     * Set API key. API key is generated inside QuickPay Manager.
     * API key is only required if IP request is coming from isn't whitelisted in QuickPay Manager
     * @param string $apiKey
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        return $this->setField('apikey', $apiKey);
    }

    /**
     * Set field data
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    protected function setField($key, $value)
    {
        $this->requestData[$key] = $value;

        return $this;
    }

    /**
     * Prepare request data array
     * @param integer $quickpayId Merchant customer ID at QuickPay
     * @return array
     */
    protected function createInitialData($quickpayId)
    {
        return array(
            'protocol' => self::API_VERSION,
            'channel' => 'creditcard',
            'msgtype' => null,
            'merchant' => $quickpayId,
            'ordernumber' => null,
            'amount' => null,
            'currency' => null,
            'autocapture' => '0',
            'cardnumber' => null,
            'expirationdate' => null,
            'cvd' => null,
            'mobilenumber' => null,
            'smsmessage' => null,
            'acquirers' => null,
            'cardtypelock' => null,
            'transaction' => null,
            'description' => null,
            'group' => null,
            'splitpayment' => null,
            'finalize' => null,
            'cardhash' => null,
            'testmode' => '0',
            'fraud_remote_addr' => null,
            'fraud_http_accept' => null,
            'fraud_http_accept_language' => null,
            'fraud_http_accept_encoding' => null,
            'fraud_http_accept_charset' => null,
            'fraud_http_referer' => null,
            'fraud_http_user_agent' => null,
            'apikey' => null
        );
    }

    /**
     * Build request
     * @return array Request hash
     */
    protected function buildRequestData()
    {
        $request = array();

        $md5string = '';

        foreach ($this->requestData as $key => $value) {
            if ($value !== null) {
                $request[$key] = $value;
                $md5string .= $value;
            }
        }

        $request['md5check'] = md5($md5string . $this->md5check);

        return $request;
    }

    /**
     * Setup cURL
     */
    protected function setupCurl()
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_URL, $this->apiUrl);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($this->curl, CURLOPT_POST, true);
    }

    /**
     * Skip verification of the servers SSL Certificate (Not recommended)
     */
    public function skipSSLVerification()
    {
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
    }

    /**
     * Send request to QuickPay Payment API
     * @throws Exception If unable to parse response
     * @return Response Response from QuickPay
     */
    public function send()
    {
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->buildRequestData());

        return new Response(curl_exec($this->curl), $this->md5check);
    }
}
