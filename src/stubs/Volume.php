<?php

namespace panlatent\craft\enums\stubs;

use Craft;
use craft\elements\Asset;
use craft\elements\db\AssetQuery;
use craft\models\Volume as CraftVolume;

enum Volume: string
{
    public function volume(): CraftVolume
    {
        return Craft::$app->getVolumes()->getVolumeByHandle($this->value);
    }

    public function find(): AssetQuery
    {
        return Asset::find()->volume($this->value);
    }

    public function new(array $config = []): Asset
    {
        $asset = new Asset($config);
        $asset->setVolumeId($this->volume()->id);
        return $asset;
    }
}