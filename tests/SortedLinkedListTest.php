<?php

namespace DavidVarney\SortedLinkedList\Tests;

use DavidVarney\SortedLinkedList\SortedLinkedList;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SortedLinkedListTest extends TestCase
{
    public function testAddAndOrderWithInts()
    {
        $list = new SortedLinkedList();
        $list->add(5);
        $list->add(1);
        $list->add(3);
        $this->assertSame([1,3,5], $list->toArray());
    }

    public function testAddAndOrderWithStrings()
    {
        $list = new SortedLinkedList();
        $list->add('banana');
        $list->add('apple');
        $list->add('cherry');
        $this->assertSame(['apple','banana','cherry'], $list->toArray());
    }

    public function testRemoveExistingValue()
    {
        $list = new SortedLinkedList();
        $list->add(2);
        $list->add(1);
        $list->add(3);
        $this->assertTrue($list->remove(2));
        $this->assertSame([1,3], $list->toArray());
    }

    public function testRemoveNonExistingValueReturnsFalse()
    {
        $list = new SortedLinkedList();
        $list->add(1);
        $this->assertFalse($list->remove(999));
    }

    public function testTypeEnforcementThrowsOnMixedTypes()
    {
        $this->expectException(InvalidArgumentException::class);
        $list = new SortedLinkedList();
        $list->add(1);
        $list->add('string');
    }

    public function testIsEmptyAndClear()
    {
        $list = new SortedLinkedList();
        $this->assertTrue($list->isEmpty());
        $list->add(1);
        $this->assertFalse($list->isEmpty());
        $list->clear();
        $this->assertTrue($list->isEmpty());
    }

    public function testContains()
    {
        $list = new SortedLinkedList();
        $list->add('a');
        $this->assertTrue($list->contains('a'));
        $this->assertFalse($list->contains('b'));
    }

    public function testToString()
    {
        $list = new SortedLinkedList();
        $list->add('banana');
        $list->add('apple');
        $list->add('cherry');

        // default glue is ", "
        $this->assertSame('apple, banana, cherry', $list->toString());

        // custom glue should be honored
        $this->assertSame('apple|banana|cherry', $list->toString('|'));
    }

    public function testGetType()
    {
        $list = new SortedLinkedList();

        // empty list should have null type
        $this->assertNull($list->getType());

        // after adding ints, type should be 'int'
        $list->add(10);
        $this->assertSame('int', $list->getType());

        // clear resets type to null
        $list->clear();
        $this->assertNull($list->getType());

        // after adding strings, type should be 'string'
        $list->add('a');
        $this->assertSame('string', $list->getType());
    }

    public function testToArray()
    {
        $list = new SortedLinkedList();

        // empty list should return an empty array
        $this->assertSame([], $list->toArray());

        // ints
        $list->add(3);
        $list->add(1);
        $list->add(2);
        $this->assertSame([1,2,3], $list->toArray());

        // clear and strings
        $list->clear();
        $list->add('z');
        $list->add('x');
        $list->add('y');
        $this->assertSame(['x','y','z'], $list->toArray());
    }

    public function testEnforceTypeSetsTypeViaReflection()
    {
        $list = new SortedLinkedList();

        $refClass = new \ReflectionClass($list);
        $method = $refClass->getMethod('enforceType');
        $method->setAccessible(true);

        // call with int
        $method->invoke($list, 42);
        $this->assertSame('int', $list->getType());

        // clear and call with string
        $list->clear();
        $method->invoke($list, 'hello');
        $this->assertSame('string', $list->getType());
    }

    public function testEnforceTypeThrowsOnMixedTypesViaReflection()
    {
        $this->expectException(\InvalidArgumentException::class);

        $list = new SortedLinkedList();
        $refClass = new \ReflectionClass($list);
        $method = $refClass->getMethod('enforceType');
        $method->setAccessible(true);

        // initial type int
        $method->invoke($list, 1);

        // invoking with string should throw
        $method->invoke($list, 'not-int');
    }

    public function testCompareIntsViaReflection()
    {
        $list = new SortedLinkedList();

        // ensure type is int
        $list->add(2);

        $refClass = new \ReflectionClass($list);
        $method = $refClass->getMethod('compare');
        $method->setAccessible(true);

        // a < b => negative
        $this->assertLessThan(0, $method->invoke($list, 1, 2));
        // a == b => 0
        $this->assertSame(0, $method->invoke($list, 2, 2));
        // a > b => positive
        $this->assertGreaterThan(0, $method->invoke($list, 3, 2));
    }

    public function testCompareStringsViaReflection()
    {
        $list = new SortedLinkedList();

        // ensure type is string
        $list->add('b');

        $refClass = new \ReflectionClass($list);
        $method = $refClass->getMethod('compare');
        $method->setAccessible(true);

        // strcmp semantics: 'a' < 'b'
        $this->assertLessThan(0, $method->invoke($list, 'a', 'b'));
        $this->assertSame(0, $method->invoke($list, 'c', 'c'));
        $this->assertGreaterThan(0, $method->invoke($list, 'z', 'y'));
    }
}
