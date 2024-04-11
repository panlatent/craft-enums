<?php

namespace panlatent\craft\enums\stubs;

use craft\elements\Entry;
use craft\helpers\ArrayHelper;
use craft\models\EntryType as CraftEntryType;

/**
 * @method Section section()
 * @method string handle()
 */
enum EntryType
{
    public function type(): CraftEntryType
    {
        return ArrayHelper::firstWhere($this->section()->section()->getEntryTypes(), 'handle', $this->handle());
    }

    public function new(array $config = []): Entry
    {
        $entry = $this->section()->new($config);
        $entry->setTypeId($this->type()->id);
        return $entry;
    }
}