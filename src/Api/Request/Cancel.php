<?php namespace Kameli\Quickpay\Api\Request;

class Cancel extends Request
{
    /**
	 * Setup API cancel request
	 * @param int $quickpayId
	 * @param string $md5check
	 * @param boolean $apiUrl
	 * @param boolean $verifySSL
	 */
    public function __construct($quickpayId = null, $md5check = null, $apiUrl = false, $verifySSL = true)
    {
        parent::__construct($quickpayId, $md5check, $apiUrl, $verifySSL);
        $this->setField('msgtype', 'cancel');
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
