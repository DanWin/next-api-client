<?php

namespace Edudip\Next\ApiClient;

/**
 * @author Marc Steinert <m.steinert@edudip.com>
 * @copyright edudip GmbH
 * @package edudip next Api Client
 */

use Edudip\Next\ApiClient\Error\AuthenticationException;
use Edudip\Next\ApiClient\Error\ResponseException;

abstract class AbstractRequest
{
    // @var int Request timeout in seconds
    const TIMEOUT = 10;

    // @var string User agent string to send in http requests
    const USER_AGENT = 'edudip/next-api-client (github.com/edudip/next-api-client)';

    /**
     * @throws AuthenticationException
     * @throws ResponseException
     */
    protected static function getRequest( string $endpoint, array $params = array())
    {
        return self::makeRequest('GET', $endpoint, $params);
    }

    /**
     * @throws AuthenticationException
     * @throws ResponseException
     */
    protected static function postRequest( string $endpoint, array $params = array())
    {
        return self::makeRequest('POST', $endpoint, $params);
    }

    /**
     * @throws AuthenticationException
     * @throws ResponseException
     */
    protected static function deleteRequest( string $endpoint, array $params = array())
    {
        return self::makeRequest('DELETE', $endpoint, $params);
    }

    /**
     * @throws AuthenticationException
     * @throws ResponseException
     */
    protected static function putRequest( string $endpoint, array $params = array())
    {
        return self::makeRequest('PUT', $endpoint, $params);
    }

    /**
     * @throws ResponseException
     * @throws AuthenticationException
     */
    protected static function makeRequest($httpVerb, $endpoint, $params = array())
    {
        $apiKey = trim(EdudipNext::getApiKey());

        if (! $apiKey) {
            throw new AuthenticationException('Please provide an API key');
        }

        $httpHeaders = array(
            'Accept: application/json',
            'Authorization: Bearer ' . $apiKey,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::buildEndpointUrl($endpoint));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
        curl_setopt($ch, CURLOPT_USERAGENT, self::USER_AGENT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::TIMEOUT);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        switch (strtoupper($httpVerb)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, self::buildPostString($params));
                break;

            case 'GET':
                $query = http_build_query($params, '', '&');
                curl_setopt($ch, CURLOPT_URL, self::buildEndpointUrl($endpoint) . '?' . $query);
                break;

            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($ch, CURLOPT_POSTFIELDS, self::buildPostString($params));
                break;

            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, self::buildPostString($params));
                break;
        }

        $responseContents = curl_exec($ch);
        $responseHeaders = curl_getinfo($ch);
        curl_close($ch);

        if (array_key_exists('http_code', $responseHeaders)) {
            if ($responseHeaders['http_code'] === 401) {
                throw new AuthenticationException('API returned http status 401 (unauthorized)');
            }
        }

        $json = json_decode($responseContents, true);

        if ($json === null) {
            throw new ResponseException('API returned non-json response');
        }

        if (array_key_exists('success', $json) && ! $json['success']) {
            if (array_key_exists('error', $json)) {
                throw new ResponseException('API returned an error: ' . is_array($json['error']) ? json_encode($json['error']) : $json['error']);
            } else if (array_key_exists('message', $json)) {
                throw new ResponseException('API returned an error: ' . $json['message']);
            } else {
                throw new ResponseException('API returned an error');
            }
        }

        if (array_key_exists('http_code', $responseHeaders)) {
            if ($responseHeaders['http_code'] >= 400) {
                throw new ResponseException('API returned http status code ' . $responseHeaders['http_code']);
            }
        }

        return $json;
    }

    /**
     * @param array $params
     * @return string
     */
    private static function buildPostString(array $params) : string
    {
        $postString = '';
        
        foreach ($params as $key => $value) {
            $postString .= $key.'='.$value.'&';
        }

        $postString = rtrim($postString, '&');

        return $postString;
    }

    /**
     * @param $endpoint
     * @return string
     */
    private static function buildEndpointUrl($endpoint) : string
    {
        return sprintf(
            '%s/%s',
            EdudipNext::getApiBase(),
            ltrim($endpoint, '/')
        );
    }
}
