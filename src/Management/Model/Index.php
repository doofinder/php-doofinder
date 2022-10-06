<?php

namespace Doofinder\Management\Model;

use Doofinder\Shared\Interfaces\ModelInterface;

/**
 * Model with data of a given index
 */
class Index implements ModelInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $preset;

    /**
     * @var array
     */
    private $options;

    /**
     * @var array<DataSource>
     */
    private $dataSources;

    /**
     * @param string $name
     * @param string $preset
     * @param string $options
     * @param array<DataSource> $dataSources
     */
    private function __construct($name, $preset, $options, $dataSources)
    {
        $this->name = $name;
        $this->preset = $preset;
        $this->options = $options;
        $this->dataSources = $dataSources;
    }

    /**
     * @param array $data
     * @return Index
     */
    public static function createFromArray(array $data)
    {
        $dataSources = array_map(function (array $dataSource) {
            return DataSource::createFromArray($dataSource);
        }, $data['datasources']);

        return new self(
            $data['name'],
            $data['preset'],
            $data['options'],
            $dataSources
        );
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'preset' => $this->preset,
            'options' => $this->options,
            'datasources' => array_map(function (DataSource $dataSource) {
                return $dataSource->jsonSerialize();
            }, $this->dataSources)
        ];
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
    public function getPreset()
    {
        return $this->preset;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return DataSource[]
     */
    public function getDataSources()
    {
        return $this->dataSources;
    }
}