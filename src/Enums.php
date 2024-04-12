<?php

namespace panlatent\craft\enums;

use Craft;
use craft\generator\BaseGenerator;
use craft\helpers\ArrayHelper;
use craft\models\Section;
use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\Factory;
use Nette\PhpGenerator\PhpNamespace;
use panlatent\craft\enums\stubs\Categories as CategoriesStub;
use panlatent\craft\enums\stubs\EntryType as EntryTypeStub;
use panlatent\craft\enums\stubs\Field as FieldStub;
use panlatent\craft\enums\stubs\Section as SectionStub;
use panlatent\craft\enums\stubs\Tags as TagsStub;
use panlatent\craft\enums\stubs\Volume as VolumeStub;
use ReflectionEnum;
use ReflectionFunctionAbstract;
use Twig\TwigFilter;

/**
 * Create enums from sections and fields
 */
class Enums extends BaseGenerator
{
    /**
     * @var string
     */
    private string $namespace;

    /**
     * @inheritdoc
     */
    public function run(): bool
    {
        $this->namespace = $this->namespacePrompt('Enums namespace:', [
            'default' => "$this->baseNamespace\\enums",
        ]);

        $this->writeFromStub(FieldStub::class, Craft::$app->getFields()->getAllFields(...));
        $this->writeFromStub(SectionStub::class, Craft::$app->getSections()->getAllSections(...));
        $this->writeFromStub(CategoriesStub::class, Craft::$app->getCategories()->getAllGroups(...));
        $this->writeFromStub(TagsStub::class, Craft::$app->getTags()->getAllTagGroups(...));
        $this->writeFromStub(VolumeStub::class, Craft::$app->getVolumes()->getAllVolumes(...));
        $this->writeFromStub(EntryTypeStub::class, $this->getEntryTypeCases(...), $this->getEntryTypeMethods());

        $this->command->success("**Enums created!**");
        return true;
    }

    public function writeFromStub(string $stub, callable $cases, array $dynamicMethods = []): void
    {
        $enum = $this->createEnum($stub);
        $this->makeCases($enum, $cases);
        $this->makeMethods($enum, $dynamicMethods);

        $ref = new ReflectionEnum($stub);
        $content = file_get_contents($ref->getFileName());
        preg_match_all('#use\s+(?<namespace>[a-zA-Z_][a-zA-Z0-9_]*(?:\\\[a-zA-Z_][a-zA-Z0-9_]*)*)(\s+as\s+(?<alias>[a-zA-Z_][a-zA-Z0-9_]*)|)\s*#', $content, $matches);
        $uses = array_combine($matches['namespace'], $matches['alias']);

        $this->writeEnum($enum, $uses);
    }

    protected function createEnum(string $stub, string $className = null): EnumType
    {
        $ref = new ReflectionEnum($stub);
        $enum = new EnumType($className ?: $ref->getShortName());
        if ($ref->getBackingType() !== null) {
            $enum->setType($ref->getBackingType()->getName());
        }

        $lines = file($ref->getFileName());
        foreach ($ref->getMethods() as $refMethod) {
            if (in_array($refMethod->getName(), ['cases', 'from', 'tryFrom'])) {
                continue;
            }
            $method = (new Factory())->fromMethodReflection($refMethod);
            $method->setBody($this->getMethodBody($lines, $refMethod));
            $enum->addMember($method);
        }

        return $enum;
    }

    protected function makeCases(EnumType $enum, callable $cases): void
    {
        $items = $cases();
        usort($items, function ($a, $b) {
            return $a->handle <=> $b->handle;
        });
        $type = $enum->getType();
        foreach ($items as $item) {
            $enum->addCase(ucfirst($item->handle), $type ? $item->handle : null);
        }
    }

    protected function makeMethods(EnumType $enum, array $methods): void
    {
        foreach ($methods as $name => $options) {
            $enum->addMethod($name)
                ->setReturnType($options['return'])
                ->setBody($options['body']);
        }
    }

    private function writeEnum(EnumType $enum, array $uses = []): void
    {
        $namespace = (new PhpNamespace($this->namespace));
        foreach ($uses as $class => $alias) {
            $namespace->addUse($class, $alias ?: null);
        }
        $namespace->add($enum);
        $this->writePhpClass($namespace);
    }

    private function getMethodBody(array $lines, ReflectionFunctionAbstract $method): string
    {
        $start = $method->getStartLine();
        for (; $start >= count($lines); $start++) {
            if (trim($lines[$start - 1]) === '{') {
                break;
            }
        }
        $end = $method->getEndLine() - 1;
        $body = array_slice($lines, $start + 1, $end - $start - 1);
        return implode("\n", array_map(fn($line) => substr(rtrim($line), 8), $body));
    }

    private function getEntryTypeCases(): array
    {
        return array_map(function ($type) {
            $handle = $type->getSection()->handle . (($type->getSection()->type === Section::TYPE_SINGLE || $type->handle === 'default') ? '' : ucfirst($type->handle));
            return new class($handle,  $type->getSection()->handle, $type->handle) {
                public function __construct(public string $handle, public string $section, public $type) {}
            };
        }, Craft::$app->getSections()->getAllEntryTypes());
    }

    private function getEntryTypeMethods(): array
    {
        $methods = ['section' => ['return' => $this->namespace . '\\Section'], 'handle' => ['return' => 'string']];

        $content = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'EntryType.php.twig');
        $twig = clone Craft::$app->getView()->getTwig();
        $twig->addFilter(new TwigFilter('ucfirst', ucfirst(...)));
        $twig->addFilter(new TwigFilter('column', ArrayHelper::getColumn(...)));
        $twig->addFilter(new TwigFilter('unique', array_unique(...)));
        $template = $twig->createTemplate($content);

        $cases = $this->getEntryTypeCases();
        foreach (array_keys($methods) as $name) {
            $methods[$name]['body'] = $template->render([
                'method' => $name,
                'cases' => $cases,
            ]);
        }

        return $methods;
    }
}