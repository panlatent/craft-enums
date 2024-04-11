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
composer require panlatent/craft-enum
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

Reference: [CraftCMS Docs](https://craftcms.com/docs/5.x/extend/generator.html#usage)

```bash
php craft make enums
```

Generate enums to `src/enums`
```bash
ddev craft make enums --path=src
```

### Coding

See generate codes.



References
-----------




License
-------
The Element Messages is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
