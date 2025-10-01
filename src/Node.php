<?php

namespace DavidVarney\SortedLinkedList;

class Node
{
    /**
     * @var int|string Value of the node
     */
    public int|string $value;
    /**
     * @var Node|null Pointer to the next node
     */
    public ?Node $next = null;

    public function __construct(int|string $value)
    {
        // Assign the incoming value
        $this->value = $value;
    }
}
