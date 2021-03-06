<?php namespace Kameli\Quickpay\Api\Request;

class Authorize extends Request
{
    /**
     * Setup API authorize request
     * @param int $quickpayId
     * @param string $md5check
     * @param boolean $apiUrl
     * @param boolean $verifySSL
     */
    public function __construct($quickpayId = null, $md5check = null, $apiUrl = false, $verifySSL = true)
    {
        parent::__construct($quickpayId, $md5check, $apiUrl, $verifySSL);
        $this->setField('msgtype', 'authorize');
    }

    /**
     * Defines if its creditcard or mobile (Mobilpenge) payment. Default is creditcard.
     * Only required for mobilpenge (mobile)
     * @param string $channel
     * @return Authorize
     */
    public function setChannel($channel)
    {
        return $this->setField('channel', $channel);
    }

    /**
     * A value by merchant's own choice. Must be unique for each transaction.
     * Usually an incrementing sequence. The value may be reflected in the your bank account list.
     * @param string $ordernumber
     * @return Authorize
     */
    public function setOrderNumber($ordernumber)
    {
        return $this->setField('ordernumber', $ordernumber);
    }

    /**
     * The transaction amount in its smallest unit. In example, 1 EUR is written 100.
     * @param int $amount
     * @return Authorize
     */
    public function setAmount($amount)
    {
        return $this->setField('amount', $amount);
    }

    /**
     * The transaction currency as the 3-letter ISO 4217 alphabetical code.
     * See http://quickpay.net/features/multi-currency/ for more information.
     * @param string $currency
     * @return Authorize
     */
    public function setCurrency($currency)
    {
        return $this->setField('currency', $currency);
    }

    /**
     * If set to '1', the transaction will be captured automatically - provided that the authorize was succesful.
     * See http://quickpay.net/features/autocapture/ for more information.
     * @param boolean $autocapture
     * @return Authorize
     */
    public function setAutoCapture($autocapture)
    {
        return $this->setField('autocapture', $autocapture ? '1' : '0');
    }

    /**
     * The refund card number
     * @param int $cardnumber
     * @return Authorize
     */
    public function setCardNumber($cardnumber)
    {
        return $this->setField('cardnumber', $cardnumber);
    }

    /**
     * The refund card expiration date. Format is YYMM
     * @param string $expdate
     * @return Authorize
     */
    public function setExpirationDate($expdate)
    {
        return $this->setField('expirationdate', $expdate);
    }

    /**
     * The refund card verification data
     * @param int $cvd
     * @return Authorize
     */
    public function setCVD($cvd)
    {
        return $this->setField('cvd', $cvd);
    }

    /**
     * Mobile number. Currently only danish mobile numbers supported and must be prefixed with 45
     * @param int $number
     * @return Authorize
     */
    public function setMobileNumber($number)
    {
        return $this->setField('mobilenumber', $number);
    }

    /**
     * Confirmation SMS message inserted into 'Please confirm payment to {merchant name} for {smsmessage}
     * amount {currency} {amount}'
     * @param string $message
     * @return Authorize
     */
    public function setSmsMessage($message)
    {
        return $this->setField('smsmessage', $message);
    }

    /**
     * What acquirer should transaction be sent to. Possible to enter prioritized comma separated
     * list (highest,...,lowest). If Acquirer is registered unavailable/error a lower priority acquirer is used.
     * @param string $acquirers
     * @return $this
     */
    public function setAcquirers($acquirers)
    {
        return $this->setField('acquirers', $acquirers);
    }

    /**
     * Lock to card type. Multiple card types allowed by comma separation.
     * See http://quickpay.net/features/cardtypelock/ for possible values.
     * @param string $cardtypelock
     * @return Authorize
     */
    public function setCardTypeLock($cardtypelock)
    {
        return $this->setField('cardtypelock', $cardtypelock);
    }

    /**
     * Enables multiple partial capture. http://quickpay.net/features/split-payment/
     * @param boolean $split
     * @return Authorize
     */
    public function setSplitPayment($split)
    {
        return $this->setField('splitpayment', $split ? '1' : '0');
    }

    /**
     * Include cardhash in response
     * @param boolean $hash
     * @return Authorize
     */
    public function setCardHash($hash)
    {
        return $this->setField('cardhash', $hash ? '1' : '0');
    }
}
