<?php

namespace panlatent\craft\enums\stubs;

use craft\elements\Category as CategoryElement;
use craft\elements\db\CategoryQuery;

enum Categories: string
{
    public function find(): CategoryQuery
    {
        return CategoryElement::find()->group($this->value);
    }
}