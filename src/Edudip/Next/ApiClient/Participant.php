<?php

namespace Edudip\Next\ApiClient;

use JsonSerializable;

/**
 * @author Marc Steinert <m.steinert@edudip.com>
 * @copyright edudip GmbH
 * @package edudip next Api Client
 */

final class Participant implements JsonSerializable
{
    // @var string
    private $email;

    // @var string
    private $firstname;

    // @var string
    private $lastname;

    // @var array
    private $registeredDates;

	/**
	 * @param string $email
	 * @param string $firstname
	 * @param string $lastname
	 */
    public function __construct(string $email, string $firstname, string $lastname)
    {
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

	/**
	 * @param array $dates
	 */
    public function setRegisteredDates(array $dates)
    {
        $this->registeredDates = $dates;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
        ];
    }

	/**
	 * @return array
	 */
    public function jsonSerialize() : array
    {
        return $this->toArray();
    }
}
