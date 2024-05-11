<?php

namespace panlatent\craft\enums;

use craft\events\RegisterComponentTypesEvent;
use craft\generator\Command;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\Event;

class Extension extends Component implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        if (version_compare($app->getVersion(), '5.0.0', '>=')) {
            /** @noinspection PhpUndefinedClassConstantInspection */
            $name = Command::EVENT_REGISTER_GENERATORS;
        } else {
            /** @noinspection PhpUndefinedClassConstantInspection */
            $name = Command::EVENT_REGISTER_GENERATOR_TYPES;
        }

        Event::on(
            Command::class,
            $name,
            function(RegisterComponentTypesEvent $e) {
                $e->types[] = Enums::class;
            }
        );
    }
}