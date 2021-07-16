<?php

namespace Edudip\Next\ApiClient;

use DateTime;
use Exception;
use JsonSerializable;

/**
 * @author Marc Steinert <m.steinert@edudip.com>
 * @copyright edudip GmbH
 * @package edudip next Api Client
 */

final class WebinarDate implements JsonSerializable
{
    // @var int
    private $id;

    // @var DateTime
    private $date;

    // @var int
    private $duration;

	/**
	 * @param DateTime $date
	 * @param int $duration
	 */
    public function __construct(DateTime $date, int $duration)
    {
        $this->date = $date;
        $this->duration = $duration;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return DateTime
     */
    public function getDate() : DateTime
    {
        return $this->date;
    }

	/**
	 * @return int
	 */
	public function getDuration() : int
    {
        return $this->duration;
    }

	/**
	 * @return array
	 */
    public function jsonSerialize() : array
    {
        return [
            'date' => $this->date->format('Y-m-d H:i:s'),
            'duration' => $this->duration,
        ];
    }

	/**
	 * Unboxes a webinar date json string back into a
	 *  WebinarDate object
	 * @param array $input
	 * @return self
	 * @throws Exception
	 */
    public static function deserialize(array $input) : WebinarDate
    {
        $webinarDate = new self(
            new DateTime($input['date']),
            $input['duration']
        );

        $webinarDate->setId($input['id']);

        return $webinarDate;
    }

	/**
	 * Tests, if the given input string is a valid Datetime
	 *  string in the form of "YYYY-MM-DD HH:MM:SS"
	 * @param string $input
	 * @return bool
	 */
    public static function validateDateString(string $input) : bool
    {
        $fmtStr = 'Y-m-d H:i:s';
        $dt = DateTime::createFromFormat($fmtStr, $input);

        return (
            $dt !== false &&
            $dt->format($fmtStr) === $input
        );
    }
}
