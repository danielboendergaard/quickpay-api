<?php namespace Kameli\Quickpay\Api;

use Kameli\Quickpay\Exception;
use SimpleXMLElement;

class Response extends \Kameli\Quickpay\Response
{
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
        'fraudreport'
    );

    /**
     * The MD5 string to ensure data integrity
     * @var null|string
     */
    protected $md5check;

    /**
     * The response array
     * @var array
     */
    protected $response;

    /**
     * Create a QuickPay API Response
     * @param string $rawResponse
     * @param string $md5check
     * @throws \Kameli\Quickpay\Exception
     */
    public function __construct($rawResponse, $md5check)
    {
        $this->md5check = $md5check;

        try {
            $this->response = $this->createResponseData(new SimpleXMLElement($rawResponse));
        } catch (Exception $e) {
            throw new Exception(
                "Error parsing response. Error: {$e->getMessage()}. Response: {$rawResponse}",
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Get Response value
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getField($key, $default = null)
    {
        return isset($this->response[$key]) ? $this->response[$key] : $default;
    }

    /**
     * Verify response md5check. Use this to verify response came from QuickPay
     * @return boolean
     */
    public function isValid()
    {
        $md5string = '';

        foreach (static::$responseFields as $key) {
            if (array_key_exists($key, $this->response)) {
                $md5string .= $this->response[$key];
            }
        }
        return strcmp($this->response['md5check'], md5($md5string . $this->md5check)) === 0;
    }

    /**
     * Convert the xml response to an array
     * @param SimpleXMLElement $xml
     * @return array
     */
    protected function createResponseData(SimpleXMLElement $xml)
    {
        return array(
            'msgtype'          => (string) $xml->msgtype,
            'ordernumber'      => (string) $xml->ordernumber,
            'amount'           => $xml->amount != '' ? (int) $xml->amount : '',
            'currency'         => (string) $xml->currency,
            'time'             => (string) $xml->time,
            'state'            => (string) $xml->state,
            'qpstat'           => (string) $xml->qpstat,
            'qpstatmsg'        => (string) $xml->qpstatmsg,
            'chstat'           => (string) $xml->chstat,
            'chstatmsg'        => (string) $xml->chstatmsg,
            'merchant'         => (string) $xml->merchant,
            'merchantemail'    => (string) $xml->merchantemail,
            'transaction'      => $xml->transaction != '' ? (int) $xml->transaction : '',
            'cardtype'         => (string) $xml->cardtype,
            'cardnumber'       => (string) $xml->cardnumber,
            'cardhash'         => (string) $xml->cardhash,
            'cardexpire'       => (string) $xml->cardexpire,
            'acquirer'         => (string) $xml->acquirer,
            'splitpayment'     => $xml->splitpayment != '' ? (int) $xml->splitpayment : '',
            'fraudprobability' => (string) $xml->fraudprobability,
            'fraudremarks'     => (string) $xml->fraudremarks,
            'fraudreport'      => (string) $xml->fraudreport,
            'md5check'         => (string) $xml->md5check
        );
    }
}
