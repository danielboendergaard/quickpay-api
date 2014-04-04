<?php namespace Kameli\QuickpayApi\Form;

class Subscribe extends Builder
{
    /**
     * Create a new form builder object from the Quickpay account information for a subscribe request
     * @param int|null $quickpayId
     * @param string|null $md5check
     */
    public function __construct($quickpayId = null, $md5check = null)
    {
        parent::__construct($quickpayId, $md5check);
        $this->setField('msgtype', 'subscribe');
    }

    /**
     * Add subscription to this subscription group
     * @param string $group
     * @return $this
     */
    public function setGroup($group)
    {
        return $this->setField('group', $group);
    }
}
