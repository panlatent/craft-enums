<?php

namespace panlatent\craft\enums\stubs;

use Craft;
use craft\elements\db\EntryQuery;
use craft\elements\Entry;
use craft\helpers\ArrayHelper;
use craft\models\Section as CraftSection;

enum Section: string
{
    /**
     * @return CraftSection
     */
    public function section(): CraftSection
    {
        return Craft::$app->getSections()->getSectionByHandle($this->value);
    }

    public function find(): EntryQuery
    {
        return Entry::find()->section($this->value);
    }

    public function findOne(mixed $criteria = null): ?Entry
    {
        $query = $this->find();
        if ($criteria !== null) {
            if (!ArrayHelper::isAssociative($criteria)) {
                $criteria = ['id' => $criteria];
            }
            Craft::configure($query, $criteria);
        }
        return $query->one();
    }

    /**
     * @param mixed|null $criteria
     * @return Entry[]
     */
    public function findAll(mixed $criteria = null): array
    {
        $query = $this->find();
        if ($criteria !== null) {
            if (!ArrayHelper::isAssociative($criteria)) {
                $criteria = ['id' => $criteria];
            }
            Craft::configure($query, $criteria);
        }
        return $query->all();
    }

    public function new(array $config = []): Entry
    {
        $entry = new Entry($config);
        $entry->sectionId = $this->section()->id;
        return $entry;
    }
}