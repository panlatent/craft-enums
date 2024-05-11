Craft Enums
============

The Craft Enum is a PHP enum generator. 
It generates all Fields, Section, Volume ... handles write to code as cases.

```php
// Get a section
$section = Section::Posts->section() 
// Create a entry with sectionId
$entry = Section::Posts->new()
// Entry query with section
$entryQuery = Section::Post->find();
// Replace string handle (Not recommended)
$section = Craft::$app->secionts->getSectionByHandle(Section::Posts->value);
```

    Note: You cannot use PHP enumerations to directly replace string. You should write `getSectionByHandle(Section::Posts->value)` instead of `getSectionByHandle(Section::Posts)`(error).
          Maybe, never try to use enums instead of strings or constants, but use the convenience methods of the enum class.

Requirements
------------
+ PHP 8.2
+ Craft CMS 4.3.5 or later.

Installation
------------

```bash
composer require panlatent/craft-enum --dev
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
The project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
