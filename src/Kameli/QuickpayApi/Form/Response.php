<?php namespace Kameli\QuickpayApi\Form;

use Kameli\QuickpayApi\QuickpayApi;

class Response extends QuickpayApi
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
     * The response status codes
     * @var array
     */
    protected static $statusCodes = array(
        '000' => 'Approved',
        '001' => 'Rejected by acquirer. See field "chstat" and "chstatmsg" for further explanation.',
        '002' => 'Communication error.',
        '003' => 'Card expired.',
        '004' => 'Transition is not allowed for transaction current state.',
        '005' => 'Authorization is expired.',
        '006' => 'Error reported by acquirer.',
        '007' => 'Error reported by QuickPay.',
        '008' => 'Error in request data.',
        '009' => 'Payment aborted by shopper'
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
     * Get the message type
     * @return string
     */
    public function getMsgType()
    {
        return $this->getField('msgtype');
    }

    /**
     * Get the order number
     * @return mixed
     */
    public function getOrderNumber()
    {
        return $this->getField('ordernumber');
    }

    /**
     * Get the requested amount it its smallest unit
     * @return mixed
     */
    public function getAmount()
    {
        return $this->getField('amount');
    }

    /**
     * The transaction currency
     * @return string
     */
    public function getCurrency()
    {
        return $this->getField('currency');
    }

    /**
     * The time of which the message was handled (UTC) Transaction timestamp is in format ISO8601.
     * @return string
     */
    public function getTime()
    {
        return $this->getField('time');
    }

    /**
     * The current state of the transaction. See http://quickpay.net/faq/transaction-states/
     * @return int
     */
    public function getState()
    {
        return $this->getField('state');
    }

    /**
     * Return code from QuickPay. See http://quickpay.net/faq/status-codes/
     * @return int
     */
    public function getQpstat()
    {
        return $this->getField('qpstat');
    }

    /**
     * A message detailing errors and warnings if any.
     * @return string
     */
    public function getQpstatmsg()
    {
        return $this->getField('qpstatmsg');
    }

    /**
     * Return code from the clearing house. Please refer to the clearing house documentation.
     * @return int
     */
    public function getChstat()
    {
        return $this->getField('chstat');
    }

    /**
     * A message from the clearing house detailing errors and warnings if any.
     * @return string
     */
    public function getChstatmsg()
    {
        return $this->getField('chstatmsg');
    }

    /**
     * The QuickPay merchant name
     * @return string
     */
    public function getMerchant()
    {
        return $this->getField('merchant');
    }

    /**
     * The QuickPay merchant email/username
     * @return string
     */
    public function getMerchantEmail()
    {
        return $this->getField('merchantemail');
    }

    /**
     * Get the transaction number
     * @return mixed
     */
    public function getTransaction()
    {
        return $this->getField('transaction');
    }

    /**
     * The card type used to authorize the transaction.
     * @return string
     */
    public function getCardType()
    {
        return $this->getField('cardtype');
    }

    /**
     * A truncated version of the card number - eg. 'XXXX XXXX XXXX 1234'.
     * @return string
     */
    public function getCardNumber()
    {
        return $this->getField('cardnumber');
    }

    /**
     * A unique hash of cardnumber
     * @return string
     */
    public function getCardHash()
    {
        return $this->getField('cardhash');
    }

    /**
     * Expire date on the card used in a 'subscribe'. Notation is 'yymm'.
     * This field will be empty for other message types than 'subscribe'.
     * @return string
     */
    public function getCardExpire()
    {
        return $this->getField('cardexpire');
    }

    /**
     * Transaction was authorized by this acquirer.
     * @return string
     */
    public function getAcquirer()
    {
        return $this->getField('acquirer');
    }

    /**
     * Spit payment enabled on transaction.
     * @return int
     */
    public function getSplitPayment()
    {
        return $this->getField('splitpayment');
    }

    /**
     * Fraud probability if fraudcheck was performed. [low|medium|high]
     * @return string
     */
    public function getFraudProbability()
    {
        return $this->getField('fraudprobability');
    }

    /**
     * Fraud remarks if fraud check was performed.
     * @return string
     */
    public function getFraudRemarks()
    {
        return $this->getField('fraudremarks');
    }

    /**
     * Fraud report if given.
     * @return string
     */
    public function getFraudReport()
    {
        return $this->getField('fraudreport');
    }

    /**
     * Will contain the calculated fee, if auto fee was activated in request.
     * @return string
     */
    public function getFee()
    {
        return $this->getField('fee');
    }

    /**
     * Get the status message
     * @return string
     */
    public function getStatusMessage()
    {
        return static::$statusCodes[$this->getField('qpstat')];
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
     * Check if the request was successful
     * @return bool
     */
    public function isSuccess()
    {
        return $this->getField('qpstat') == '000';
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
