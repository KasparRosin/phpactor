<?php

namespace Phpactor\CodeBuilder\Adapter\TolerantParser\Util;

use ArrayIterator;
use IteratorAggregate;
use Microsoft\PhpParser\Node;
use Traversable;

class ImportedNames implements IteratorAggregate
{
    private array $table;

    public function __construct(Node $node)
    {
        $this->buildTable($node);
    }

    
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->classNamesFromNode());
    }

    public function classNames(): array
    {
        return array_values($this->classNamesFromNode());
    }

    public function functionNames(): array
    {
        $names = [];
        foreach ($this->table[1] as $shortName => $resolvedName) {
            $names[$shortName] = (string) $resolvedName;
        }

        return $names;
    }

    private function classNamesFromNode(): array
    {
        $names = [];
        foreach ($this->table[0] as $shortName => $resolvedName) {
            $names[(string) $resolvedName] = (string) $resolvedName;
        }

        return $names;
    }

    private function buildTable(Node $node): void
    {
        if ('SourceFileNode' == $node->getNodeKindName()) {
            $this->table =  [
                [],
                [],
                []
            ];
            return;
        }

        $this->table = $node->getImportTablesForCurrentScope();
    }
}
