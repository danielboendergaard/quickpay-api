<?php namespace Kameli\Quickpay;

trait Configuration
{
    /**
     * The Quickpay ID
     * @var int
     */
    protected static $defaultQuickpayId;

    /**
     * The MD5 string to ensure data integrity
     * @var string
     */
    protected static $defaultMd5check;

    /**
     * Quickpay API Key
     * @var string
     */
    protected static $defaultApiKey;

    /**
     * Set the Quickpay Merchant ID
     * @param int $id
     */
    public static function setDefaultQuickpayId($id)
    {
        static::$defaultQuickpayId = $id;
    }

    /**
     * Set the MD5 check string
     * @param string $md5check
     */
    public static function setDefaultMd5Check($md5check)
    {
        static::$defaultMd5check = $md5check;
    }

    /**
     * Set the Quickpay API key
     * @param string $apiKey
     */
    public static function setDefaultApiKey($apiKey)
    {
        static::$defaultApiKey = $apiKey;
    }
}
