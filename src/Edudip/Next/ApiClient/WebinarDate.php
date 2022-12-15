<?php

namespace Edudip\Next\ApiClient;

use DateTime;
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function jsonSerialize()
    {
        return [
            'date' => $this->date->format('Y-m-d H:i:s'),
            'duration' => (int) $this->duration,
        ];
    }

    /**
     * Unboxes a webinar date json string back into a
     *  WebinarDate object
     * @return self
     */
    public static function deserialize(array $input)
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
     * @return boolean
     */
    public static function validateDateString($input)
    {
        $fmtStr = 'Y-m-d H:i:s';
        $dt = DateTime::createFromFormat($fmtStr, $input);

        return (
            $dt !== false &&
            $dt->format($fmtStr) === $input
        );
    }
}
