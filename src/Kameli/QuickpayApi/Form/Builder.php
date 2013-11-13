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
    protected $protocol = 7;

    /**
     * Test mode status
     * @var int
     */
    protected $testmode = 0;

    /**
     * The Quickpay ID
     * @var int
     */
    protected $merchant;

    /**
     * The md5 checksum
     * @var string
     */
    protected $md5Check;

    /**
     * The form fields
     * @var array
     */
    protected $fields = [];

    /**
     * The custom form fields
     * @var array
     */
    protected $customFields = [];

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
     * Make a new form builder object from the Quickpay account information
     * @param int $quickpayID
     * @param string $md5check
     */
    public function __construct($quickpayID, $md5check) {
        $this->merchant = $quickpayID;
        $this->md5Check = $md5check;
    }

    /**
     * Get the form action
     * @return string
     */
    public function getAction() {
        return static::FORM_ACTION;
    }

    /**
     * Set the language on the payment gateway
     * @param string $code
     * @return $this
     */
    public function setLanguage($code) {
        $this->setField('language', $code);

        return $this;
    }

    /**
     * Set the order number
     * @param string $ordernumber
     * @return $this
     */
    public function setOrdernumber($ordernumber) {
        $this->setField('ordernumber', $ordernumber);

        return $this;
    }

    /**
     * Set the amount in it's smallest unit 1,23 DKK is 123
     * @param int $amount
     * @return $this
     */
    public function setAmount($amount) {
        $this->setField('amount', $amount);

        return $this;
    }

    /**
     * Set the currency
     * @param string $currency
     * @return $this
     */
    public function setCurrency($currency) {
        $this->setField('currency', $currency);

        return $this;
    }

    /**
     * Set the url to redirect to after a successful transaction
     * @param string $url
     * @return $this
     */
    public function setContinueUrl($url) {
        $this->setField('continueurl', $url);

        return $this;
    }

    /**
     * Set the url to redirect to after a cancelled transaction
     * @param string $url
     * @return $this
     */
    public function setCancelUrl($url) {
        $this->setField('cancelurl', $url);

        return $this;
    }

    /**
     * Set the callback url to
     * @param string $url
     * @return $this
     */
    public function setCallbackUrl($url) {
        $this->setField('callbackurl', $url);

        return $this;
    }

    /**
     * Set the description
     * @param string $description
     * @return $this
     */
    public function setDescription($description) {
        $this->setField('description', $description);

        return $this;
    }

    /**
     * Enable auto capture
     * @return $this
     */
    public function enableAutocapture() {
        $this->setField('autocapture', 1);

        return $this;
    }

    /**
     * Enable auto fee
     * @return $this
     */
    public function enableAutofee() {
        $this->setField('autofee', 1);

        return $this;
    }

    /**
     * Enable test mode
     * @return $this
     */
    public function enableTestmode() {
        $this->testmode = 1;

        return $this;
    }

    /**
     * Set a field value
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setField($name, $value) {
        $this->fields[$name] = $value;

        return $this;
    }

    /**
     * Set the fields from an array of attributes
     * @param array $inputFields
     * @return $this
     */
    public function setFields($inputFields) {
        foreach ($inputFields as $key => $value)
        {
            $this->setField($key, $value);
        }

        return $this;
    }

    /**
     * Prepare the fields and calculate the md5 checksum
     */
    protected function prepareFields() {
        $reservedFields = array('protocol', 'merchant', 'testmode');

        foreach ($reservedFields as $field) {
            $this->fields[$field] = $this->$field;
        }

        // Sort for MD5 calculation
        $sorted = [];

        foreach (static::$md5checkFields as $field) {
            if (isset($this->fields[$field])) {
                $sorted[$field] = $this->fields[$field];
            }
        }

        $sorted['md5check'] = md5(implode("", $sorted) . $this->md5Check);

        $this->fields = $sorted;
    }

    /**
     * Get the form fields
     * @return string
     */
    public function getFields() {
        $this->prepareFields();

        $html = '';

        foreach(array_merge($this->fields, $this->customFields) as $key => $value)
        {
            $html .= '<input type="hidden" name="'.$key.'" value="'.$value.'">';
        }

        return $html;
    }

    /**
     * Alias for getFields
     * @return string
     */
    public function getFormFields() {
        return $this->getFields();
    }

    /**
     * Set a custom field value
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setCustom($name, $value) {
        $this->customFields['CUSTOM_'.$name] = $value;

        return $this;
    }
}