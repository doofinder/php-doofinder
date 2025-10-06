<?php

namespace Doofinder\Management\Model;

use Doofinder\Shared\Interfaces\ModelInterface;

/**
 * Model with data of a given item
 */
class Item implements ModelInterface
{
    const SETTED_FIELDS = ['title', 'description', 'link', 'image_link', 'availability', 'price', 'sale_price', 'brand', 'gtin', 'mpn'];

    /**
     * @var string
     */
    private $id;

    /**
     * @var string|null
     */
    private $dfGroupingId;

    /**
     * @var bool|null
     */
    private $dfGroupLeader;

    /**
     * @var double|null
     */
    private $dfManualBoost;

    /**
     * @var array<string>|string
     */
    private $categories;

    /**
     * @var double|null
     */
    private $bestPrice;

    /**
     * @var array
     */
    private $additionalFields = [];

    /**
     * @param string|null $id
     * @param string|null $dfGroupingId
     * @param string|null $dfGroupLeader
     * @param double|null $dfManualBoost
     * @param array<string>|string|null $categories
     * @param double|null $bestPrice
     * @param string|null $additionalFields
     */
    private function __construct($id , $dfGroupingId, $dfGroupLeader, $dfManualBoost, $categories, $bestPrice, $additionalFields)
    {
        $this->id = $id;
        $this->dfGroupingId = $dfGroupingId;
        $this->dfGroupLeader = $dfGroupLeader;
        $this->dfManualBoost = $dfManualBoost;
        $this->categories = $categories;
        $this->bestPrice = $bestPrice;
        $this->additionalFields = $additionalFields;
    }

    /**
     * @param array $data
     * @return Item
     */
    public static function createFromArray(array $data)
    {
        return new self(
            array_key_exists('id', $data)? $data['id'] : null,
            array_key_exists('df_grouping_id', $data)? $data['df_grouping_id'] : null,
            array_key_exists('df_group_leader', $data)? $data['df_group_leader'] : null,
            array_key_exists('df_manual_boost', $data)? $data['df_manual_boost'] : null,
            array_key_exists('categories', $data)? $data['categories'] : null,
            array_key_exists('best_price', $data)? $data['best_price'] : null,
            array_filter($data, function ($key) {
                return in_array($key, self::SETTED_FIELDS);
            }, ARRAY_FILTER_USE_KEY)
        );
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array_merge([
            'id' => $this->id,
            'df_grouping_id' => $this->dfGroupingId,
            'df_group_leader' => $this->dfGroupLeader,
            'df_manual_boost' => $this->dfManualBoost,
            'categories' => $this->categories,
            'best_price' => $this->bestPrice
        ],
            $this->additionalFields
        );
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getDfGroupingId()
    {
        return $this->dfGroupingId;
    }

    /**
     * @return bool|null
     */
    public function getDfGroupLeader()
    {
        return $this->dfGroupLeader;
    }

    /**
     * @return double|null
     */
    public function getDfManualBoost()
    {
        return $this->dfManualBoost;
    }

    /**
     * @return array<string>|string|null
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return double|null
     */
    public function getBestPrice()
    {
        return $this->bestPrice;
    }

    /**
     * @return array
     */
    public function getAdditionalFields()
    {
        return $this->additionalFields;
    }
}