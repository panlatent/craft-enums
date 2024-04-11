<?php

namespace panlatent\craft\enums\stubs;

use Craft;
use craft\elements\db\EntryQuery;
use craft\elements\Entry;
use craft\models\Section as CraftSection;

enum Section: string
{
    public function find(): EntryQuery
    {
        return Entry::find()->section($this->value);
    }

    /**
     * @return CraftSection
     */
    public function self(): CraftSection
    {
        return Craft::$app->getSections()->getSectionByHandle($this->value);
    }

    public function new(array $config = []): Entry
    {
        $entry = new Entry($config);
        $entry->sectionId = $this->self()->id;
        return $entry;
    }

    /**
     * @return Entry[]
     */
    public function types(): array
    {
        return $this->self()->getEntryTypes();
    }
}