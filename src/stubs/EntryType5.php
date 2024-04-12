<?php

namespace panlatent\craft\enums\stubs;

use Craft;
use craft\elements\Entry;
use craft\models\EntryType as CraftEntryType;

enum EntryType5: string
{
    public function type(): CraftEntryType
    {
        return Craft::$app->getEntries()->getEntryTypeByHandle($this->value);
    }

    public function new(array $config = []): Entry
    {
        $entry = new Entry($config);
        $entry->setTypeId($this->type()->id);
        return $entry;
    }
}