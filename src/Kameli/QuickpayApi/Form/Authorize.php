<?php namespace Kameli\QuickpayApi\Form;

class Authorize extends Builder {

    /**
     * Create a new form builder object from the Quickpay account information for an authorize request
     * @param int|null $quickpayId
     * @param string|null $md5check
     */
    public function __construct($quickpayId = null, $md5check = null) {
        parent::__construct($quickpayId, $md5check);
        $this->setField('msgtype', 'authorize');
    }
}