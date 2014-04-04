<?php namespace Kameli\QuickpayApi\Form;

abstract class Builder
{
    /**
     * The URL for the form action
     */
    const FORM_ACTION = 'https://secure.quickpay.dk/form/';

    /**
     * The protocol version
     * @var int
     */
    const PROTOCOL_VERSION = 7;

    /**
     * The Quickpay ID
     * @var int
     */
    protected static $quickpayId;

    /**
     * The MD5 string to ensure data integrity
     * @var string
     */
    protected static $md5check;

    /**
     * The md5 checksum
     * @var string
     */
    protected $md5Check;

    /**
     * The form fields
     * @var array
     */
    protected $fields = array(
        'testmode' => 0
    );

    /**
     * The custom form fields
     * @var array
     */
    protected $customFields = array();

    /**
     * The fields to include in the md5 checksum
     * @var array
     */
    protected static $md5checkFields = array(
        'protocol',
        'msgtype',
        'merchant',
        'language',
        'ordernumber',
        'amount',
        'currency',
        'continueurl',
        'cancelurl',
        'callbackurl',
        'autocapture',
        'autofee',
        'cardtypelock',
        'description',
        'group',
        'testmode',
        'splitpayment',
        'forcemobile',
        'deadline',
        'cardhash'
    );

    /**
     * Create a new form builder object from the Quickpay account information
     * @param int|null $quickpayId
     * @param string|null $md5check
     */
    public function __construct($quickpayId = null, $md5check = null)
    {
        $this->md5Check = $md5check ? $md5check : static::$md5check;

        $this->fields['protocol'] = static::PROTOCOL_VERSION;
        $this->fields['merchant'] = $quickpayId ? $quickpayId : static::$quickpayId;
    }

    /**
     * Set the Quickpay ID
     * @param int $quickpayId
     */
    public static function setQuickpayId($quickpayId)
    {
        static::$quickpayId = $quickpayId;
    }

    /**
     * Set the MD5 check string
     * @param string $md5check
     */
    public static function setMd5Check($md5check)
    {
        static::$md5check = $md5check;
    }

    /**
     * Set the language on the payment gateway
     * @param string $code
     * @return $this
     */
    public function setLanguage($code)
    {
        return $this->setField('language', $code);
    }

    /**
     * Set the order number
     * @param string $ordernumber
     * @return $this
     */
    public function setOrdernumber($ordernumber)
    {
        return $this->setField('ordernumber', $ordernumber);
    }

    /**
     * Set the amount in it's smallest unit 1,23 DKK is 123
     * @param int $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        return $this->setField('amount', $amount);
    }

    /**
     * Set the currency
     * @param string $currency
     * @return $this
     */
    public function setCurrency($currency)
    {
        return $this->setField('currency', $currency);
    }

    /**
     * Set the url to redirect to after a successful transaction
     * @param string $url
     * @return $this
     */
    public function setContinueUrl($url)
    {
        return $this->setField('continueurl', $url);
    }

    /**
     * Set the url to redirect to after a cancelled transaction
     * @param string $url
     * @return $this
     */
    public function setCancelUrl($url)
    {
        return $this->setField('cancelurl', $url);
    }

    /**
     * Set the callback url to
     * @param string $url
     * @return $this
     */
    public function setCallbackUrl($url)
    {
        return $this->setField('callbackurl', $url);
    }

    /**
     * Lock to card type. Multiple card types allowed by comma separation.
     * @param string $cards
     * @return $this
     */
    public function setCardTypeLock($cards)
    {
        return $this->setField('cardtypelock', $cards);
    }

    /**
     * Set the description
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        return $this->setField('description', $description);
    }

    /**
     * Transaction deadline in seconds from request to QuickPay is made.
     * @param int $seconds
     * @return $this
     */
    public function setDeadline($seconds)
    {
        return $this->setField('deadline', $seconds);
    }

    /**
     * Enable auto capture
     * @return $this
     */
    public function enableAutocapture()
    {
        return $this->setField('autocapture', 1);
    }

    /**
     * Enable auto fee
     * @return $this
     */
    public function enableAutofee()
    {
        return $this->setField('autofee', 1);
    }

    /**
     * Enable test mode
     * @return $this
     */
    public function enableTestmode()
    {
        return $this->setField('testmode', 1);
    }

    /**
     * Enable split payment on transaction
     * @return $this
     */
    public function enableSplitPayment()
    {
        return $this->setField('splitpayment', 1);
    }

    /**
     * Include cardhash in response.
     * @return $this
     */
    public function includeCardHash()
    {
        return $this->setField('cardhash', 1);
    }

    /**
     * Force showing mobile form.
     * @return $this
     */
    public function forceMobile()
    {
        return $this->setField('forcemobile', 1);
    }

    /**
     * Set a field value
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setField($name, $value)
    {
        $this->fields[$name] = $value;

        return $this;
    }

    /**
     * Set the fields from an array of attributes
     * @param array $inputFields
     * @return $this
     */
    public function setFields($inputFields)
    {
        foreach ($inputFields as $key => $value)
        {
            $this->setField($key, $value);
        }

        return $this;
    }

    /**
     * Set a custom field value
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setCustom($name, $value)
    {
        $this->customFields['CUSTOM_'.$name] = $value;

        return $this;
    }

    /**
     * Prepare the fields and calculate the md5 checksum
     */
    protected function prepareFields()
    {
        $sorted = [];

        foreach (static::$md5checkFields as $field) {
            if (isset($this->fields[$field])) {
                $sorted[$field] = $this->fields[$field];
            }
        }

        $sorted['md5check'] = md5(implode('', $sorted) . $this->md5Check);

        $this->fields = $sorted;
    }

    /**
     * Get the form action
     * @return string
     */
    public function getFormAction()
    {
        return static::FORM_ACTION;
    }

    /**
     * Get the hidden form inputs
     * @return string
     */
    public function getFormInputs()
    {
        $this->prepareFields();

        $html = '';

        foreach (array_merge($this->fields, $this->customFields) as $key => $value) {
            $html .= '<input type="hidden" name="'.$key.'" value="'.$value.'">';
        }

        return $html;
    }
}
