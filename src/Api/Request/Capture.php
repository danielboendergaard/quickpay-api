<?php namespace Kameli\Quickpay\Api\Request;

class Capture extends Request
{
    /**
	 * Setup API capture request
	 * @param int $quickpayId
	 * @param string $md5check
	 * @param boolean $apiUrl
	 * @param boolean $verifySSL
	 */
    public function __construct($quickpayId = null, $md5check = null, $apiUrl = false, $verifySSL = true)
    {
        parent::__construct($quickpayId, $md5check, $apiUrl, $verifySSL);
        $this->setField('msgtype', 'capture');
    }

    /**
     * The transaction amount in its smallest unit. In example, 1 EUR is written 100.
     * @param int $amount
     * @return Capture
     */
    public function setAmount($amount)
    {
        return $this->setField('amount', $amount);
    }

    /**
     * Finalizes multiple partial capture.
     * When set transaction will go into a closed state and no more captures will be possible.
     * @param boolean $finalize
     * @return Capture
     */
    public function setFinalize($finalize)
    {
        return $this->setField('finalize', $finalize ? '1' : '0');
    }

    /**
     * A transaction id from a previous transaction.
     * @param int $transaction
     * @return Capture
     */
    public function setTransaction($transaction)
    {
        return $this->setField('transaction', $transaction);
    }
}
