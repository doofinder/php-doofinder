<?php

namespace Doofinder\Management\Model;

use Doofinder\Shared\Interfaces\ModelInterface;

class SearchEngineList implements ModelInterface
{
    /**
     * @var array<SearchEngine>
     */
    private $searchEngines = [];

    public static function createFromArray(array $data)
    {
        $entity = new self();
        foreach ($data as $searchEngineArray) {
            $entity->searchEngines[] = SearchEngine::createFromArray($searchEngineArray);
        }

        return $entity;
    }

    /**
     * @return array<SearchEngine>
     */
    public function getSearchEngines()
    {
        return $this->searchEngines;
    }

    public function jsonSerialize()
    {
        $searchEnginesArray = [];

        foreach ($this->searchEngines as $searchEngine) {
            $searchEnginesArray[] = $searchEngine->jsonSerialize();
        }

        return $searchEnginesArray;
    }
}