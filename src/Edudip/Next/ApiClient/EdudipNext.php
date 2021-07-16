<?php

namespace Edudip\Next\ApiClient;

/**
 * @author Marc Steinert <m.steinert@edudip.com>
 * @copyright edudip GmbH
 * @package edudip next Api Client
 */

final class EdudipNext
{
    // @var string The edudip next API key to be used to authenticate requests
    public static $apiKey;

    // @var string
    public static $apiBase = 'https://api.edudip-next.com/api';

    /**
     * Returns the currently used API key.
     */
    public static function getApiKey() : string
    {
        return self::$apiKey;
    }

    /**
     * Sets the API key to use for all requests.
     */
    public static function setApiKey(string $apiKey)
    {
        self::$apiKey = $apiKey;
    }

	/**
	 * @return string
	 */
    public static function getApiBase() : string
    {
        return self::$apiBase;
    }

    /**
     * Sets a new location for the API, useful for local debugging.
     */
    public static function setApiBase(string $apiBase)
    {
        self::$apiBase = $apiBase;
    }
}
