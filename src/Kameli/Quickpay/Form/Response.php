<?php namespace Kameli\Quickpay\Form;

class Response extends \Kameli\Quickpay\Response
{
    /**
     * The MD5 string to ensure data integrity
     * @var string
     */
    protected static $md5check;

    /**
     * Fields in the response
     * @var array
     */
    protected static $responseFields = array(
        'msgtype',
        'ordernumber',
        'amount',
        'currency',
        'time',
        'state',
        'qpstat',
        'qpstatmsg',
        'chstat',
        'chstatmsg',
        'merchant',
        'merchantemail',
        'transaction',
        'cardtype',
        'cardnumber',
        'cardhash',
        'cardexpire',
        'acquirer',
        'splitpayment',
        'fraudprobability',
        'fraudremarks',
        'fraudreport',
        'fee'
    );

    /**
     * The response array
     * @var array
     */
    protected $response;

    /**
     * The custom variables
     * @var array
     */
    protected $custom = array();

    /**
     * Create a response object from the callback post data
     * @param array $postData
     */
    public function __construct($postData)
    {
        $this->response = $this->parsePostData($postData);
    }

    /**
     * Get a value from the response
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getField($key, $default = null)
    {
        if (array_key_exists($key, $this->response)) {
            return $this->response[$key];
        }

        if (array_key_exists($key, $this->custom)) {
            return $this->custom[$key];
        }

        return $default;
    }

    /**
     * Check if the request was valid
     * @param string|null $md5check
     * @return bool
     */
    public function isValid($md5check = null)
    {
        $md5check = $md5check ? $md5check : static::$md5check;

        $md5string = '';

        foreach (static::$responseFields as $key) {
            if (array_key_exists($key, $this->response)) {
                $md5string .= $this->response[$key];
            }
        }

        return strcmp($this->response['md5check'], md5($md5string . $md5check)) === 0;
    }

    /**
     * Parse the POST data
     * @param array $postData
     * @return array
     */
    protected function parsePostData($postData)
    {
        // Parse the standard response fields
        foreach (array_merge(static::$responseFields, array('md5check')) as $name) {
            $response[$name] = isset($postData[$name]) ? $postData[$name] : null;
        }

        // Parse custom variables
        foreach ($postData as $name => $value) {
            if (strpos($name, 'CUSTOM_') === 0 and strlen($name) > 7) {
                $this->custom[substr($name, 7)] = $value;
            }
        }

        return isset($response) ? $response : array();
    }
}
