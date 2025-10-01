<?php

namespace DavidVarney\SortedLinkedList\Tests;

use DavidVarney\SortedLinkedList\Node;
use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase
{
    public function testNodeConstruction(): void
    {
        $node = new Node(123);
        $this->assertSame(123, $node->value);
        $this->assertNull($node->next);

        $nodeStr = new Node('abc');
        $this->assertSame('abc', $nodeStr->value);
        $this->assertNull($nodeStr->next);
    }

    public function testNodeLinking(): void
    {
        $first = new Node(1);
        $second = new Node(2);
        $first->next = $second;

        $this->assertSame($second, $first->next);
        $this->assertNull($second->next);
    }
}
