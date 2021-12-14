<?php

namespace Doofinder\Management\Model;

use Doofinder\Shared\Interfaces\ModelInterface;

class SearchEngine implements ModelInterface
{
    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $hashid;

    /**
     * @var array
     */
    private $indices;

    /**
     * @var bool
     */
    private $inactive;

    /**
     * @var string
     */
    private $language;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $searchUrl;

    /**
     * @var string|null
     */
    private $siteUrl;

    /**
     * @var bool
     */
    private $stopwords;

    /**
     * @var string
     */
    private $platform;

    /**
     * @var bool
     */
    private $hasGrouping;

    private function __construct(
        $language,
        $name,
        $currency,
        $hashid,
        $indices,
        $inactive,
        $searchUrl,
        $siteUrl,
        $stopwords,
        $platform,
        $hasGrouping
    ) {
        $this->language = $language;
        $this->name = $name;
        $this->currency = $currency;
        $this->hashid = $hashid;
        $this->indices = $indices;
        $this->inactive = $inactive;
        $this->searchUrl = $searchUrl;
        $this->siteUrl = $siteUrl;
        $this->stopwords = $stopwords;
        $this->platform = $platform;
        $this->hasGrouping = $hasGrouping;
    }

    /**
     * @param array $data
     * @return SearchEngine
     */
    public static function createFromArray(array $data)
    {
        return new self(
            $data['language'],
            $data['name'],
            $data['currency'],
            $data['hashid'],
            $data['indices'],
            $data['inactive'],
            $data['search_url'],
            $data['site_url'],
            $data['stopwords'],
            $data['platform'],
            $data['has_grouping']
        );
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getHashid()
    {
        return $this->hashid;
    }

    /**
     * @return array
     */
    public function getIndices()
    {
        return $this->indices;
    }

    /**
     * @return bool
     */
    public function isInactive()
    {
        return $this->inactive;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSearchUrl()
    {
        return $this->searchUrl;
    }

    /**
     * @return string|null
     */
    public function getSiteUrl()
    {
        return $this->siteUrl;
    }

    /**
     * @return bool
     */
    public function isStopwords()
    {
        return $this->stopwords;
    }

    /**
     * @return string
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * @return bool
     */
    public function isHasGrouping()
    {
        return $this->hasGrouping;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'language' => $this->language,
            'name' => $this->name,
            'currency' => $this->currency,
            'hashid' => $this->hashid,
            'indices' => $this->indices,
            'inactive' => $this->inactive,
            'search_url' => $this->searchUrl,
            'site_url' => $this->siteUrl,
            'stopwords' => $this->stopwords,
            'platform' => $this->platform,
            'has_grouping' => $this->hasGrouping
        ];
    }
}