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
    // @var ?int
    private $id;

    // @var DateTime
    private $date;

    // @var int
    private $duration;

    // @var ?string
    private $status;

    // @var ?string
    private $recorder_auth_key;

    // @var ?DateTime
    private $date_end;

    // @var ?bool
    private $is_editable;

    // @var ?array
    private $participants_certificates_types;

    // @var mixed
    private $participants_certificates_code;

    /**
     * @param DateTime $date
     * @param int $duration
     * @param int|null $id
     * @param string|null $status
     * @param string|null $recorder_auth_key
     * @param DateTime|null $date_end
     * @param bool|null $is_editable
     * @param array|null $participants_certificates_types
     * @param $participants_certificates_code
     */
    public function __construct(DateTime $date,
                                int $duration,
                                ?int $id = null,
                                ?string $status = null,
                                ?string $recorder_auth_key = null,
                                ?DateTime $date_end = null,
                                ?bool $is_editable = null,
                                ?array $participants_certificates_types = null,
                                $participants_certificates_code = null
    )
    {
        $this->id = $id;
        $this->date = $date;
        $this->duration = $duration;
        $this->status = $status;
        $this->recorder_auth_key = $recorder_auth_key;
        $this->date_end = $date_end;
        $this->is_editable = $is_editable;
        $this->participants_certificates_types = $participants_certificates_types;
        $this->participants_certificates_code = $participants_certificates_code;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getId() : ?int
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
     * @return string|null
     */
    public function getStatus() : ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     */
    public function setStatus( ?string $status ): void
    {
        $this->status = $status;
    }

    /**
     * @return string|null
     */
    public function getRecorderAuthKey() : ?string
    {
        return $this->recorder_auth_key;
    }

    /**
     * @param string|null $recorder_auth_key
     */
    public function setRecorderAuthKey( ?string $recorder_auth_key ): void
    {
        $this->recorder_auth_key = $recorder_auth_key;
    }

    /**
     * @return DateTime|null
     */
    public function getDateEnd() : ?DateTime
    {
        return $this->date_end;
    }

    /**
     * @param DateTime|null $date_end
     */
    public function setDateEnd( ?DateTime $date_end ): void
    {
        $this->date_end = $date_end;
    }

    /**
     * @return bool|null
     */
    public function getIsEditable() : ?bool
    {
        return $this->is_editable;
    }

    /**
     * @param bool|null $is_editable
     */
    public function setIsEditable( ?bool $is_editable ): void
    {
        $this->is_editable = $is_editable;
    }

    /**
     * @return array|null
     */
    public function getParticipantsCertificatesTypes() : ?array
    {
        return $this->participants_certificates_types;
    }

    /**
     * @param array|null $participants_certificates_types
     */
    public function setParticipantsCertificatesTypes( ?array $participants_certificates_types ): void
    {
        $this->participants_certificates_types = $participants_certificates_types;
    }

    /**
     * @return mixed
     */
    public function getParticipantsCertificatesCode()
    {
        return $this->participants_certificates_code;
    }

    /**
     * @param mixed $participants_certificates_code
     */
    public function setParticipantsCertificatesCode( $participants_certificates_code ): void
    {
        $this->participants_certificates_code = $participants_certificates_code;
    }

    /**
     * @return array
     */
    public function jsonSerialize() : array
    {
        return [
            'id' => $this->id,
            'date' => $this->date->format('Y-m-d H:i:s'),
            'duration' => $this->duration,
            'status' => $this->status,
            'recorder_auth_key' => $this->recorder_auth_key,
            'date_end' => ($this->date_end instanceof DateTime) ? $this->date_end->format('Y-m-d H:i:s') : null,
            'is_editable' => $this->is_editable,
            'participants_certificates_types' => $this->participants_certificates_types,
            'participants_certificates_code' => $this->participants_certificates_code,
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
        $date_end = null;
        if(is_string($input['date_end']) && self::validateDateString($input['date_end'])){
            $date_end = DateTime::createFromFormat('Y-m-d H:i:s', $input['date_end']);
        }
        $webinarDate = new self(
            new DateTime($input['date']),
            $input['duration'],
            $input['id'] ?? null,
            $input['status'] ?? null,
            $input['recorder_auth_key'] ?? null,
            $date_end,
            $input['is_editable'] ?? null,
            $input['participants_certificates_types'] ?? null,
            $input['participants_certificates_code'] ?? null,
        );

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
