<?php

namespace panlatent\craft\enums\stubs;

use Craft;
use craft\elements\Asset;

enum Volume: string
{
    public function new(): Asset
    {
        $asset = new Asset();
        $asset->setVolumeId(Craft::$app->getVolumes()->getVolumeByHandle($this->value)->id);
        return $asset;
    }
}