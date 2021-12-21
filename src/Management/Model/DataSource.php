<?php

namespace Doofinder\Management\Model;

use Doofinder\Shared\Interfaces\ModelInterface;

/**
 * Model with data of a given data source
 */
class DataSource implements ModelInterface
{
    /**
     * @var array<DataSourceOption>
     */
    const FQCN_OPTIONS = [
        'file' => DataSourceOptionFile::class,
        'shopify' => DataSourceOptionShopify::class,
        'bigcommerce' => DataSourceOptionBigCommerce::class,
        'ekm' => DataSourceOptionEkm::class,
        'magento2' => DataSourceOptionMagento::class
    ];

    /**
     * @var DataSourceOption
     */
    private $options;

    /**
     * @var string
     */
    private $type;

    /**
     * @param DataSourceOption $options
     * @param string $type
     */
    private function __construct($options, $type)
    {
        $this->options = $options;
        $this->type = $type;
    }

    /**
     * @param array $data
     * @return DataSource
     */
    public static function createFromArray(array $data)
    {
        /** @var class-string<DataSourceOption> $fqcn */
        $fqcn = self::FQCN_OPTIONS[$data['type']];

        return new self(
            $fqcn::createFromArray($data['options']),
            $data['type']
        );
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'type' => $this->type,
            'options' => $this->options->jsonSerialize()
        ];
    }
}