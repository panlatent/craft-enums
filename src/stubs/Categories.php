<?php

namespace panlatent\craft\enums\stubs;

use Craft;
use craft\elements\Category;
use craft\elements\db\CategoryQuery;
use craft\models\CategoryGroup;

enum Categories: string
{
    public function group(): CategoryGroup
    {
        return Craft::$app->getCategories()->getGroupByHandle($this->value);
    }

    public function find(): CategoryQuery
    {
        return Category::find()->group($this->value);
    }

    public function new(array $config = []): Category
    {
        $category = new Category($config);
        $category->groupId = $this->group()->id;
        return $category;
    }
}