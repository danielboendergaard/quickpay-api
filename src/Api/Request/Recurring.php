<?php namespace Kameli\Quickpay\Api\Request;

class Recurring extends Request
{
    /**
	 * Setup API recurring request.
	 * @param int $quickpayId
	 * @param string $md5check
	 * @param boolean $apiUrl
	 * @param boolean $verifySSL
	 */
    public function __construct($quickpayId = null, $md5check = null, $apiUrl = false, $verifySSL = true)
    {
        parent::__construct($quickpayId, $md5check, $apiUrl, $verifySSL);
        $this->setField('msgtype', 'recurring');
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
     * A transaction id from a previous transaction.
     * @param int $transaction
     * @return Cancel
     */
    public function setTransaction($transaction)
    {
        return $this->setField('transaction', $transaction);
    }
}
