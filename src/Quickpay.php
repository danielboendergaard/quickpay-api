<?php namespace Kameli\Quickpay;

abstract class Quickpay
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
     * Quickpay API Key
     * @var string
     */
    protected static $apiKey;

    /**
     * Set the Quickpay ID
     * @param int $quickpayId
     */
    public static function setGlobalQuickpayId($quickpayId)
    {
        static::$quickpayId = $quickpayId;
    }

    /**
     * Set the MD5 check string
     * @param string $md5check
     */
    public static function setGlobalMd5Check($md5check)
    {
        static::$md5check = $md5check;
    }

    /**
     * Set the Quickpay API key
     * @param string $apiKey
     */
    public static function setGlobalApiKey($apiKey)
    {
        static::$apiKey = $apiKey;
    }
}
