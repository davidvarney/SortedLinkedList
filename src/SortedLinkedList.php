<?php

namespace DavidVarney\SortedLinkedList;

use InvalidArgumentException;

class SortedLinkedList
{
    private ?Node $head = null;
    private ?string $type = null; // "int" or "string"

    public function __construct()
    {
        $this->head = null;
    }

    /**
     * Add a value to the linked list in sorted order.
     *
     * @param int|string $value
     * @return void
     */
    public function add(int|string $value): void
    {
        // Need to enforce the type consistency per specification and for comparison reasons
        $this->enforceType($value);

        // Create our new node to add
        $newNode = new Node($value);


        // Look at what we currently have, if nothing, add it as the head
        // Or, look at the current value and compare it to the new value to
        // see if it should be the new head
        if ($this->head === null || $this->compare($value, $this->head->value) < 0) {
            $newNode->next = $this->head;
            $this->head = $newNode;
            return;
        }

        // Otherwise, find the correct spot to insert the new node
        $current = $this->head;
        while ($current->next !== null && $this->compare($value, $current->next->value) >= 0) {
            $current = $current->next;
        }

        $newNode->next = $current->next;
        $current->next = $newNode;
    }

    /**
     * Remove a value from the linked list.
     *
     * @param int|string $value
     * @return bool True if value was removed
     */
    public function remove(int|string $value): bool
    {
        // Need to enfore the value type for comparison sakes
        $this->enforceType($value);

        // Can't remove something if it's not there
        if ($this->head === null) {
            return false;
        }

        if ($this->head->value === $value) {
            // We simply overwrite the existing head with the next node
            // We set the next attribute to null in the Node class as a default
            // so if there is only one node we should get back an empty array
            $this->head = $this->head->next;
            return true;
        }

        // Traverse the list to find the node to remove
        $current = $this->head;
        while ($current->next !== null && $current->next->value !== $value) {
            $current = $current->next;
        }

        // Return false if the value was not found 
        if ($current->next === null) {
            return false;
        }

        // Override the next pointer to basically delete the node
        $current->next = $current->next->next;
        return true;
    }

    /**
     * Get all values in the linked list as an array.
     *
     * @return array<int|string>
     */
    public function toArray(): array
    {
        $result = [];
        $current = $this->head;

        while ($current !== null) {
            $result[] = $current->value;
            $current = $current->next;
        }

        return $result;
    }

    /**
     * Check if the list contains a value.
     * 
     * Bonus Method - not required but useful
     *
     * @param int|string $value
     * @return bool
     */
    public function contains(int|string $value): bool
    {
        $this->enforceType($value);

        $current = $this->head;
        while ($current !== null) {
            if ($current->value === $value) {
                return true;
            }
            $current = $current->next;
        }

        return false;
    }

    /**
     * Check if the list is empty.
     * 
     * Bonus Method - not required but useful
     * 
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->head === null;
    }

    /**
     * Clear the list.
     * 
     * Bonus Method - not required but useful
     * 
     * @return void
     */
    public function clear(): void
    {
        $this->head = null;
        $this->type = null; // Reset type as well
    }

    /**
     * Get the type of values stored in the list.
     * Returns "int", "string", or null if the list is empty.
     * 
     * Bonus Method - not required but useful
     * 
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Get a string representation of the list.
     * 
     * Bonus Method - not required but useful
     * 
     * @param string $glue String to join values with
     * @return string
     */
    public function toString(string $glue = ', '): string
    {
        return implode($glue, $this->toArray());
    }

    /**
     * Ensure type consistency.
     * Defaults to the type of the first value added.
     *
     * @param int|string $value
     * @return void
     */
    private function enforceType(int|string $value): void
    {
        $type = is_int($value) ? 'int' : 'string';
        if ($this->type === null) {
            $this->type = $type;
        } elseif ($this->type !== $type) {
            throw new InvalidArgumentException("SortedLinkedList can only hold values of type {$this->type}.");
        }
    }

    /**
     * Compare two values (int or string).
     *
     * @param int|string $a
     * @param int|string $b
     * @return int
     */
    private function compare(int|string $a, int|string $b): int
    {
        if ($this->type === 'int') {
            return $a <=> $b;
        } else { // string
            return strcmp($a, $b);
        }
    }
}

/**
 * Node class for linked list.
 * @internal Only using this for the SortedLinkedList class so really
 *           no need to place this elsewhere for the time being.
 */
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
