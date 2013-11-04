<?php namespace Kameli\QuickpayApi\Form;

class Subscribe extends Builder
{
    /**
     * Make a new form builder object from the Quickpay account information for a subscribe request
     * @param int $quickpayID
     * @param string $md5check
     */
    public function __construct($quickpayID, $md5check) {
        parent::__construct($quickpayID, $md5check);
        $this->setField('msgtype', 'subscribe');
    }
}