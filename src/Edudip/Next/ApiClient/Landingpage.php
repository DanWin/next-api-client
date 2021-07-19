<?php

namespace Edudip\Next\ApiClient;

use JsonSerializable;

/**
 * @author Marc Steinert <m.steinert@edudip.com>
 * @copyright edudip GmbH
 * @package edudip next Api Client
 */

final class Landingpage implements JsonSerializable
{
    // @var string
    private $url;

    // @var string
    private $image_url;

    // @var string
    private $image_type;

    // @var string
    private $description;

    // @var string
    private $description_short;

    // @var array
    private $category;

    /**
     * @param string $url
     * @param string $image_url
     * @param string $image_type
     * @param string $description
     * @param string $description_short
     * @param array|null $category
     */
    public function __construct(
        string $url,
        string $image_url,
        string $image_type,
        string $description,
        string $description_short,
        ?array $category
    )
    {
        $this->url = $url;
        $this->image_url = $image_url;
        $this->image_type = $image_type;
        $this->description = $description;
        $this->description_short = $description_short;
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl( string $url ): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getImageUrl() : string
    {
        return $this->image_url;
    }

    /**
     * @param string $image_url
     */
    public function setImageUrl( string $image_url ): void
    {
        $this->image_url = $image_url;
    }

    /**
     * @return string
     */
    public function getImageType() : string
    {
        return $this->image_type;
    }

    /**
     * @param string $image_type
     */
    public function setImageType( string $image_type ): void
    {
        $this->image_type = $image_type;
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription( string $description ): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescriptionShort() : string
    {
        return $this->description_short;
    }

    /**
     * @param string $description_short
     */
    public function setDescriptionShort( string $description_short ): void
    {
        $this->description_short = $description_short;
    }

    /**
     * @return array|null
     */
    public function getCategory() : ?array
    {
        return $this->category;
    }

    /**
     * @param array|null $category
     */
    public function setCategory( ?array $category ): void
    {
        $this->category = $category;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'url' => $this->url,
            'image' => [
                'url' => $this->image_url,
                'type' => $this->image_type,
            ],
            'description' => $this->description,
            'description_short' => $this->description_short,
            'category' => $this->category,
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
