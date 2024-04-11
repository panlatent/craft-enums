<?php

namespace panlatent\craft\enums\stubs;

use Craft;
use craft\base\FieldInterface;

enum Field: string
{
    /**
     * @return FieldInterface
     */
    public function field(): FieldInterface
    {
        return Craft::$app->getFields()->getFieldByHandle($this->value);
    }
}