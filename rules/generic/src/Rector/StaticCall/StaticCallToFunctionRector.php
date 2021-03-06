<?php

declare(strict_types=1);

namespace Rector\Generic\Rector\StaticCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\ConfiguredCodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\Generic\ValueObject\StaticCallToFunction;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Generic\Tests\Rector\StaticCall\StaticCallToFunctionRector\StaticCallToFunctionRectorTest
 */
final class StaticCallToFunctionRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const STATIC_CALLS_TO_FUNCTIONS = 'static_calls_to_functions';

    /**
     * @var StaticCallToFunction[]
     */
    private $staticCallsToFunctions = [];

    /**
     * @param StaticCallToFunction[] $staticCallToFunctions
     */
    public function __construct(array $staticCallToFunctions = [])
    {
        $this->staticCallsToFunctions = $staticCallToFunctions;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Turns static call to function call.', [
            new ConfiguredCodeSample(
                'OldClass::oldMethod("args");',
                'new_function("args");',
                [
                    self::STATIC_CALLS_TO_FUNCTIONS => [
                        new StaticCallToFunction('OldClass', 'oldMethod', 'new_function'),
                    ],
                ]
            ),
        ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->staticCallsToFunctions as $staticCallsToFunctions) {
            if (! $this->isObjectType($node, $staticCallsToFunctions->getClass())) {
                continue;
            }

            if (! $this->isName($node->name, $staticCallsToFunctions->getMethod())) {
                continue;
            }

            return new FuncCall(new FullyQualified($staticCallsToFunctions->getFunction()), $node->args);
        }

        return null;
    }

    public function configure(array $configuration): void
    {
        $staticCallsToFunctions = $configuration[self::STATIC_CALLS_TO_FUNCTIONS] ?? [];
        Assert::allIsInstanceOf($staticCallsToFunctions, StaticCallToFunction::class);
        $this->staticCallsToFunctions = $staticCallsToFunctions;
    }
}
