<?php namespace Kameli\QuickpayApi;

abstract class QuickpayApi
{
    /**
     * The Quickpay ID
     * @var int
     */
    protected static $quickpayId;

    /**
     * The MD5 string to ensure data integrity
     * @var string
     */
    protected static $md5check;

    /**
     * Set the Quickpay ID
     * @param int $quickpayId
     */
    public static function setQuickpayId($quickpayId)
    {
        static::$quickpayId = $quickpayId;
    }

    /**
     * Set the MD5 check string
     * @param string $md5check
     */
    public static function setMd5Check($md5check)
    {
        static::$md5check = $md5check;
    }
}
