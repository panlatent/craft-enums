<?php

namespace panlatent\craft\enums\stubs;

use Craft;
use craft\elements\db\TagQuery;
use craft\elements\Tag;
use craft\models\TagGroup;

enum Tags: string
{
    public function group(): TagGroup
    {
        return Craft::$app->getTags()->getTagGroupByHandle($this->value);
    }

    public function find(): TagQuery
    {
        return Tag::find()->group($this->value);
    }

    public function new(array $config = []): Tag
    {
        $tag = new Tag($config);
        $tag->groupId = $this->group()->id;
        return $tag;
    }
}