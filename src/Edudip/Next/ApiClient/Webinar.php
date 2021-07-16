<?php

namespace Edudip\Next\ApiClient;

use DateTime;
use Edudip\Next\ApiClient\Error\AuthenticationException;
use Edudip\Next\ApiClient\Error\InvalidArgumentException;
use Edudip\Next\ApiClient\Error\ResponseException;
use Exception;

/**
 * @author Marc Steinert <m.steinert@edudip.com>
 * @copyright edudip GmbH
 * @package edudip next Api Client
 */

class Webinar extends AbstractRequest
{
    // @var int
    private $id;

    // @var string
    private $title;

    // @var int
    private $max_participants;

    // @var int
    private $participants_count;

    // @var array
    private $participants;

    // @var array
    private $moderators;

    // @var int
    private $recording;

    // @var string
    private $registration_type;

    // @var string
    private $access;

    // @var array
    private $dates;

    // @var int
    private $users_id;

    // @var string
    private $language;

    // @var Landingpage
    private $landingpage;

    // @var ?DateTime
    private $next_date;

    // @var ?DateTime
    private $created_at;

    // @var ?DateTime
    private $updated_at;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->title = $data['title'] ?? '';
        $this->max_participants = $data['max_participants'] ?? 1;
        $this->participants = $data['participants'] ?? [];
        $this->moderators = $data['moderators'] ?? [];
        $this->recording = $data['recording'] ?? 0;
        $this->registration_type = $data['registration_type'] ?? 'series';
        $this->access = $data['access'] ?? 'all';
        $this->dates = $data['dates'] ?? [];
        $this->users_id = $data['users_id'] ?? null;
        $this->language = $data['language'] ?? null;
        if(!empty($data['landingpage'])){
            $this->landingpage = new Landingpage($data['landingpage']['url'], $data['landingpage']['image']['url'], $data['landingpage']['image']['type'], $data['landingpage']['description'], $data['landingpage']['description_short'], $data['landingpage']['category']);
        }
        if($data['next_date'] !== null && WebinarDate::validateDateString($data['next_date'])) {
            $this->next_date = DateTime::createFromFormat('Y-m-d H:i:s', $data['next_date']);
        }
        if($data['created_at'] !== null && WebinarDate::validateDateString($data['created_at'])) {
            $this->created_at = DateTime::createFromFormat('Y-m-d H:i:s', $data['created_at']);
        }
        if($data['updated_at'] !== null && WebinarDate::validateDateString($data['updated_at'])) {
            $this->updated_at = DateTime::createFromFormat('Y-m-d H:i:s', $data['updated_at']);
        }
    }

    /**
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle( string $title ) : void
    {
        $this->title = $title;
    }

    /**
     * @return array A list of WebinarDates objects, representing
     *  the dates, the webinar takes place.
     * @throws Exception
     */
    public function getDates() : array
    {
        $webinarDates = [ ];

        foreach ($this->dates as $date) {
            $webinarDates []= WebinarDate::deserialize($date);
        }

        return $webinarDates;
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getParticipants() : array
    {
        return $this->participants;
    }

    /**
     * @return array
     */
    public function getModerators() : array
    {
        return $this->moderators;
    }

    /**
     * @return int
     */
    public function getMaxParticipants(): int
    {
        return $this->max_participants;
    }

    /**
     * @param int $max_participants
     */
    public function setMaxParticipants( int $max_participants ): void
    {
        $this->max_participants = $max_participants;
    }

    /**
     * @return int
     */
    public function getRecording(): int
    {
        return $this->recording;
    }

    /**
     * @param int $recording
     */
    public function setRecording( int $recording ): void
    {
        $this->recording = $recording;
    }

    /**
     * @return string
     */
    public function getRegistrationType(): string
    {
        return $this->registration_type;
    }

    /**
     * @param string $registration_type
     */
    public function setRegistrationType( string $registration_type ): void
    {
        $this->registration_type = $registration_type;
    }

    /**
     * @return string
     */
    public function getAccess(): string
    {
        return $this->access;
    }

    /**
     * @param string $access
     */
    public function setAccess( string $access ): void
    {
        $this->access = $access;
    }

    /**
     * @return int|null
     */
    public function getUsersId(): ?int
    {
        return $this->users_id;
    }

    /**
     * @param int|null $users_id
     */
    public function setUsersId( ?int $users_id ): void
    {
        $this->users_id = $users_id;
    }

    /**
     * @return string|null
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @param string|null $language
     */
    public function setLanguage( ?string $language ): void
    {
        $this->language = $language;
    }

    /**
     * @return int
     */
    public function getParticipantsCount() : int
    {
        return $this->participants_count;
    }

    /**
     * @param int $participants_count
     */
    public function setParticipantsCount( int $participants_count ): void
    {
        $this->participants_count = $participants_count;
    }

    /**
     * @return Landingpage|null
     */
    public function getLandingpage() : ?Landingpage
    {
        return $this->landingpage;
    }

    /**
     * @param Landingpage|null $landingpage
     */
    public function setLandingpage( ?Landingpage $landingpage ): void
    {
        $this->landingpage = $landingpage;
    }

    /**
     * @return ?DateTime
     */
    public function getNextDate() : ? DateTime
    {
        return $this->next_date;
    }

    /**
     * @param ?DateTime $next_date
     */
    public function setNextDate( ?DateTime $next_date ): void
    {
        $this->next_date = $next_date;
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

    /**
     * @param Participant $participant
     * @param ?string $date If the webinar registration type is "date", provide a webinar
     *  date in the format "Y-m-d H:i:s" to which the participant should be registered to
     * @return array A list of dates, the participant may now attend with a personalized
     *  link, that can be used on that date to enter the webinar room
     * @throws InvalidArgumentException;
     * @throws AuthenticationException
     * @throws ResponseException
     */
    public function registerParticipant(Participant $participant, ?string $date = null) : array
    {
        $params = $participant->toArray();

        if ($this->registration_type === 'date') {
            if ($date === null || ! WebinarDate::validateDateString($date)) {
                throw new InvalidArgumentException(
                    'Registration type for the webinar is "date". Please provide a valid webinar date to register a participant'
                );
            }
            
            $params['webinar_date'] = $date;
        }

        $resp = self::postRequest('/webinars/' . $this->getId() . '/register-participant', $params);
        
        return $resp['registeredDates'];
    }

    /**
     * Returns a list of all webinars
     * @return array
     * @throws AuthenticationException
     * @throws ResponseException
     */
    public static function all() : array
    {
        $resp = self::getRequest('/webinars');

        $webinars = [ ];
        foreach ($resp['webinars'] as $webinarData) {
            $webinars []= new self($webinarData);
        }

        return $webinars;
    }

    /**
     * Retrieves a single webinar by id
     * @param int $webinarId
     * @return Webinar
     * @throws AuthenticationException
     * @throws ResponseException
     */
    public static function getById(int $webinarId) : Webinar
    {
        $resp = self::getRequest('/webinars/' . $webinarId);
        $webinar = new self($resp['webinar']);

        return $webinar;
    }

    /**
     * Creates a new webinar
     * @throws InvalidArgumentException
     * @throws AuthenticationException
     * @throws ResponseException
     */
    public static function create(
        string $title,
        array $webinarDates,
        int $maxParticipants,
        bool $recording,
        string $registrationType = 'series',
        string $access = 'all',
        ?int $users_id = null,
        ?string $language = null,
    ) : Webinar {
        if (count($webinarDates) === 0) {
            throw new InvalidArgumentException('Please provide at least one webinar date');
        }

        foreach ($webinarDates as $webinarDate) {
            if (! ($webinarDate instanceof WebinarDate)) {
                throw new InvalidArgumentException('Expected type WebinarDate');
            }
        }

        $params = [
            'title' => $title,
            'max_participants' => $maxParticipants,
            'recording' => $recording,
            'registration_type' => $registrationType,
            'access' => $access,
            'dates' => json_encode($webinarDates),
        ];
        if(!is_null($users_id)){
            $params['users_id'] = $users_id;
        }
        if(!is_null($language)){
            $params['language'] = $language;
        }

        $resp = self::postRequest('/webinars', $params);

        $webinar = new self($resp['webinar']);
        
        return $webinar;
    }
}
