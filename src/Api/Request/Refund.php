<?php namespace Kameli\Quickpay\Api\Request;

class Refund extends Request
{
    /**
	 * Setup API refund request
	 * @param int $quickpayId
	 * @param string  $md5check
	 * @param boolean $apiUrl
	 * @param boolean $verifySSL
	 */
    public function __construct($quickpayId = null, $md5check = null, $apiUrl = false, $verifySSL = true)
    {
        parent::__construct($quickpayId, $md5check, $apiUrl, $verifySSL);
        $this->setField('msgtype', 'refund');
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
     * A transaction id from a previous transaction.
     * @param int $transaction
     * @return Cancel
     */
    public function setTransaction($transaction)
    {
        return $this->setField('transaction', $transaction);
    }
}
