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
    private ?int $id;

    private string $title;

    private int $max_participants;

    private int $participants_count;

    private array $participants;

    private array $moderators;

    private int $recording;

    private string $registration_type;

    private bool $registration_type_editable;

    private string $access;

    // @var WebinarDate[]
    private array $dates;

    private int $users_id;

    private array $user;

    private string $language;

    private Landingpage $landingpage;

    private ?WebinarDate $next_date;

    private ?DateTime $created_at;

    private ?DateTime $updated_at;

    private ?string $slug;

    /**
     * @param array $data
     * @throws Exception
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->title = $data['title'] ?? '';
        $this->max_participants = $data['max_participants'] ?? 1;
        $this->participants = [];
	    if(!empty($data['participants'])) {
		    foreach ( $data['participants'] as $participant ) {
			    $this->participants [] = Participant::deserialize( $participant );
		    }
	    }

	    $this->moderators = $data['moderators'] ?? [];
        $this->recording = $data['recording'] ?? 0;
        $this->registration_type = $data['registration_type'] ?? 'series';
        $this->registration_type_editable = $data['registration_type_editable'] ?? null;
        $this->access = $data['access'] ?? 'all';
        $this->dates = [];
        if(!empty($data['dates'])) {
            foreach ( $data['dates'] as $date ) {
                $this->dates [] = WebinarDate::deserialize( $date );
            }
        }
        $this->users_id = $data['users_id'] ?? null;
        $this->user = $data['user'] ?? null;
        $this->language = $data['language'] ?? null;
        if(!empty($data['landingpage'])){
            $this->landingpage = new Landingpage($data['landingpage']['url'], $data['landingpage']['image']['url'], $data['landingpage']['image']['type'], $data['landingpage']['description'], $data['landingpage']['description_short'], $data['landingpage']['category']);
        }
        if(!empty($data['next_date'])) {
            $this->next_date = WebinarDate::deserialize( $data[ 'next_date' ] );
        }
        if(!empty($data['created_at']) && WebinarDate::validateDateString($data['created_at'])) {
            $this->created_at = DateTime::createFromFormat('Y-m-d H:i:s', $data['created_at']);
        }
        if(!empty($data['updated_at']) && WebinarDate::validateDateString($data['updated_at'])) {
            $this->updated_at = DateTime::createFromFormat('Y-m-d H:i:s', $data['updated_at']);
        }
        $this->slug = $data['slug'] ?? null;
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
     * @return WebinarDate[] A list of WebinarDates objects, representing
     *  the dates, the webinar takes place.
     * @throws Exception
     */
    public function getDates() : array
    {
        return $this->dates;
    }

    /**
     * @param WebinarDate[] $dates A list of WebinarDates objects, representing
     *  the dates, the webinar takes place.
     * @throws Exception
     */
    public function setDates(array $dates) : void
    {
        $this->dates = $dates;
    }

    /**
     * @param DateTime $date
     * @throws AuthenticationException
     * @throws ResponseException
     */
    public function deleteWebinarDate(DateTime $date) : void
    {
        foreach ($this->dates as $i => $webinarDate){
            if($webinarDate->getDate()->format('Y-m-d H:i:s') === $date->format('Y-m-d H:i:s')){
                if(count($this->dates) > 1) {
                    self::deleteRequest( '/webinars/' . $this->id . '/dates/' . $webinarDate->getId() );
                } else {
                    $this->deleteWebinar();
                }
                unset( $this->dates[ $i ] );
                break;
            }
        }
    }

    /**
     * @param DateTime $date
     * @param int $duration
     * @throws AuthenticationException
     * @throws ResponseException
     */
    public function addWebinarDate(DateTime $date, int $duration) : void
    {
        $found = false;
        foreach ($this->dates as $webinarDate){
            if($webinarDate->getDate()->format('Y-m-d H:i:s') === $date->format('Y-m-d H:i:s')){
                $found = true;
                break;
            }
        }
        if(!$found){
            $params = [
                'date' => $date->format('Y-m-d H:i:s'),
                'duration' => $duration,
            ];
            self::postRequest( '/webinars/' . $this->id . '/add-date', $params );
        }
    }

    /**
     * @throws AuthenticationException
     * @throws ResponseException
     */
    public function deleteWebinar() : void
    {
        if(!is_null($this->id)) {
            self::deleteRequest( '/webinars/' . $this->id );
            $this->id = null;
        }
    }

    /**
     * @return ?int
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id) : void
    {
        $this->id = $id;
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
     * @return bool|null
     */
    public function getRegistrationTypeEditable(): ?bool
    {
        return $this->registration_type_editable;
    }

    /**
     * @param bool|null $registration_type_editable
     */
    public function setRegistrationTypeEditable( ?bool $registration_type_editable ): void
    {
        $this->registration_type_editable = $registration_type_editable;
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
     * @return array|null
     */
    public function getUser(): ?array
    {
        return $this->user;
    }

    /**
     * @param array|null $user
     */
    public function setUser( ?array $user ): void
    {
        $this->user = $user;
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
     * @return ?WebinarDate
     */
    public function getNextDate() : ? WebinarDate
    {
        return $this->next_date;
    }

    /**
     * @param ?WebinarDate $next_date
     */
    public function setNextDate( ?WebinarDate $next_date ): void
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
     * @return ?string
     */
    public function getSlug() : ?string
    {
        return $this->slug;
    }

    /**
     * @param ?string $slug
     */
    public function setSlug( ?string $slug ): void
    {
        $this->slug = $slug;
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
        $params = [
	        'email' => $participant->getEmail(),
	        'firstname' => $participant->getFirstname(),
	        'lastname' => $participant->getLastname(),
        ];

        if ($this->registration_type === 'date') {
            if ($date === null || ! WebinarDate::validateDateString($date)) {
                throw new InvalidArgumentException(
                    'Registration type for the webinar is "date". Please provide a valid webinar date to register a participant'
                );
            }

            $params['webinar_date'] = $date;
        }

        $resp = self::postRequest('/webinars/' . $this->getId() . '/register-participant', $params);

        $this->participants []= $participant;

        return $resp['registeredDates'];
    }

	/**
	 * @param string $email
	 * @throws AuthenticationException
	 * @throws ResponseException
	 * @throws Exception
	 */
    public function cancelRegistration(string $email) : void
    {
        $index = null;
        $participant = null;
        foreach($this->participants as $k => $v){
            if($v->getEmail() === $email){
                $index = $k;
                $participant = $v;
                break;
            }
        }
        if(is_null($participant)){
        	throw new Exception('Participant not found');
        }
        $params = [
            'email' => $email,
            'auth_key' => $participant->getAuthKey(),
        ];
        self::postRequest('/webinars/' . $this->getId() . '/cancelRegistration', $params);
        unset($this->participants[$index]);
    }

    /**
     * Returns a list of all webinars
     * @return Webinar[]
     * @throws AuthenticationException
     * @throws ResponseException
     * @throws Exception
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
     * @throws Exception
     */
    public static function getById(int $webinarId) : Webinar
    {
        $resp = self::getRequest('/webinars/' . $webinarId);
        $webinar = new self($resp['webinar']);

        return $webinar;
    }

    /**
     * Creates a new webinar
     * @param string $title
     * @param WebinarDate[] $webinarDates
     * @param int $maxParticipants
     * @param int $recording
     * @param string $registrationType
     * @param string $access
     * @param int|null $users_id
     * @param string|null $language
     * @return Webinar
     * @throws AuthenticationException
     * @throws InvalidArgumentException
     * @throws ResponseException
     * @throws Exception
     */
    public static function create(
        string $title,
        array $webinarDates,
        int $maxParticipants,
        int $recording,
        string $registrationType = 'series',
        string $access = 'all',
        ?int $users_id = null,
        ?string $language = null
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
        if(!empty($users_id)){
            $params['users_id'] = $users_id;
        }
        if(!empty($language)){
            $params['language'] = $language;
        }

        $resp = self::postRequest('/webinars', $params);

        $webinar = new self($resp['webinar']);

        return $webinar;
    }

    /**
     * Updates an existing webinar
     * @param int $id
     * @param string|null $title
     * @param int|null $maxParticipants
     * @param int|null $recording
     * @param string|null $registrationType
     * @param string|null $access
     * @return Webinar
     * @throws AuthenticationException
     * @throws ResponseException
     * @throws Exception
     */
    public static function update(
        int $id,
        ?string $title = null,
        ?int $maxParticipants = null,
        ?int $recording = null,
        ?string $registrationType = null,
        ?string $access = null
    ) : Webinar {

        $params = [];
        if(!is_null($title)){
            $params['title'] = $title;
        }
        if(!is_null($maxParticipants)){
            $params['max_participants'] = $maxParticipants;
        }
        if(!is_null($recording)){
            $params['recording'] = $recording;
        }
        if(!is_null($registrationType)){
            $params['registration_type'] = $registrationType;
        }
        if(!is_null($access)){
            $params['access'] = $access;
        }

        $resp = self::putRequest('/webinars/' . $id, $params);

        $webinar = new self($resp['webinar']);

        return $webinar;
    }
}
