Craft Enums
============

The Craft Enum is a PHP enum generator. 
It generates all Fields, Section, Volume ... handles write to code as cases.

```php
// Replace string handle
$section = Craft::$app->secionts->getSectionByHandle(Section::Posts);
// Get a section
$section = Section::Posts->self() 
// Create a entry with sectionId
$entry = Section::Posts->new()
// Entry query with section
$entryQuery = Section::Post->find();
```

Requirements
------------
+ PHP 8.2
+ Craft CMS 4.3.5 or later.

Installation
------------

```bash
composer require panlatent/craft-enum --dev
```

If you started your project with a version of Craft earlier than 4.3.5, update and run:
```
composer require craftcms/generator --dev
```

Registering Enums generator:
```bash
use craft\events\RegisterComponentTypesEvent;
use craft\generator\Command;
use panlatent\craft\enums\Enums;
use yii\base\Event;

// CraftCMS 5.x
Event::on(
    Command::class,
    Command::EVENT_REGISTER_GENERATORS,
    function(RegisterComponentTypesEvent $e) {
        $e->types[] = Enums::class;
    }
);

// CraftCMS 4.x
Event::on(
    Command::class,
    Command::EVENT_REGISTER_GENERATOR_TYPES,
    function(RegisterComponentTypesEvent $e) {
        $e->types[] = Enums::class;
    }
);
```

Usage
------

### Generate

+ Manual

Reference: [CraftCMS Docs](https://craftcms.com/docs/5.x/extend/generator.html#usage)

```bash
php craft make enums
```

Generate enums to `src/enums`
```bash
ddev craft make enums --path=src
```

+ Automatic

It is recommended that you register events to generate real-time:

```php
use panlatent\craft\enums\Enums;
Event::on(ProjectConfig::class, ProjectConfig::EVENT_AFTER_WRITE_YAML_FILES), function() {
    (new \Symfony\Component\Process\Process(['php', 'craft', 'make', 'enums', '--path=src', '--interactive=1'], Craft::getAlias('@root'), [Enums::DISABLE_INTERACTIVE => true]))->mustRun();
});
```
We use the `Symfony Process` component to run build commands when the project changes, using `--interactive=1` and `Enums::DISABLE_INTERACTIVE => true` in order to bypass CraftCMS limitation.

### Coding

See generate codes.



References
-----------




License
-------
The Element Messages is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
