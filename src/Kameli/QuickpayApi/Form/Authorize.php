<?php namespace Kameli\QuickpayApi\Form;

class Authorize extends Builder {

    /**
     * Make a new form builder object from the Quickpay account information for an authorize request
     * @param int $quickpayID
     * @param string $md5check
     */
    public function __construct($quickpayID, $md5check) {
        parent::__construct($quickpayID, $md5check);
        $this->setField('msgtype', 'authorize');
    }
}