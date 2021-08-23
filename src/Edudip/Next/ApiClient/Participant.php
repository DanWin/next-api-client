<?php

namespace Edudip\Next\ApiClient;

use DateTime;
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

    // @var ?array
    private $registeredDates;

    // @var ?string
    private $auth_key;

    // @var ?DateTime
    private $created_at;

    // @var ?DateTime
    private $updated_at;

    /**
     * @param string $email
     * @param string $firstname
     * @param string $lastname
     */
    public function __construct(string $email, string $firstname, string $lastname, ?string $auth_key = null, ?DateTime $created_at = null, ?DateTime $updated_at = null)
    {
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->auth_key = $auth_key;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
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
            'auth_key' => $this->auth_key,
            'created_at' => $this->created_at instanceof DateTime ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at instanceof DateTime ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize() : array
    {
        return $this->toArray();
    }

	/**
	 * Unboxes a Participant data array back into a Participant object
	 * @param array $input
	 * @return self
	 */
	public static function deserialize(array $input) : Participant
	{
		$created_at = null;
		if(is_string($input['created_at']) && WebinarDate::validateDateString($input['created_at'])){
			$created_at = DateTime::createFromFormat('Y-m-d H:i:s', $input['created_at']);
		}
		$updated_at = null;
		if(is_string($input['updated_at']) && WebinarDate::validateDateString($input['updated_at'])){
			$updated_at = DateTime::createFromFormat('Y-m-d H:i:s', $input['updated_at']);
		}
		$participant = new self(
			$input['email'],
			$input['firstname'],
			$input['lastname'],
			$input['auth_key'] ?? null,
			$created_at,
			$updated_at,
		);

		return $participant;
	}

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail( string $email ): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname( string $firstname ): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname( string $lastname ): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return ?string
     */
    public function getAuthKey() : ?string
    {
        return $this->auth_key;
    }

    /**
     * @param ?string $auth_key
     */
    public function setAuthKey( ?string $auth_key ): void
    {
        $this->auth_key = $auth_key;
    }

    /**
     * @return ?DateTime
     */
    public function getCreatedAt() : ?DateTime
    {
        return $this->created_at;
    }

    /**
     * @param ?DateTime $created_at
     */
    public function setCreatedAt( ?DateTime $created_at ): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return ?DateTime
     */
    public function getUpdatedAt() : ?DateTime
    {
        return $this->updated_at;
    }

    /**
     * @param ?DateTime $updated_at
     */
    public function setUpdatedAt( ?DateTime $updated_at ): void
    {
        $this->updated_at = $updated_at;
    }


}
