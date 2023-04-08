<?php

namespace Hexlet\Validator\Tests;

use Hexlet\Validator\Validator;
use PHPUnit\Framework\TestCase;

class ArrayValidatorTest extends TestCase
{
    public function testArray(): void
    {
        $v = new Validator();
        $schema = $v->array();
        $schema2 = $v->array(); // $schema != $schema2
        $schema3 = $v->array();

        $this->assertNotEquals($schema, $schema2);
        $this->assertNotEquals($schema, $schema3);
        $this->assertTrue($schema->isValid(null));

        $schema->required();
        //$this->assertTrue($schema->isValid([]));
        $this->assertFalse($schema->isValid('42'));
        $this->assertTrue($schema2->isValid(null)); // not required()
        $this->assertFalse($schema->isValid(null));

        $this->assertTrue($schema->isValid([42, '42']));

        $schema->sizeof(2);
        $this->assertTrue($schema->isValid([1, 2]));
        $this->assertTrue($schema->isValid(['1', '2']));
        $this->assertFalse($schema->isValid([1]));

        $schema->sizeof(10)->sizeof(1);
        $this->assertTrue($schema->isValid([1]));
        $this->assertTrue($schema->isValid(['1']));
        $this->assertFalse($schema->isValid([1, 2]));

        $schema3->shape([
            'name' => $v->string()->required(),
            'age' => $v->number()->positive(),
        ]);
        $this->assertTrue($schema3->isValid(['name' => 'kolya', 'age' => 100])); // true
        $this->assertTrue($schema3->isValid(['name' => 'maya', 'age' => null])); // true
        $this->assertFalse($schema3->isValid(['name' => '', 'age' => null])); // false
        $this->assertFalse($schema3->isValid(['name' => 'ada', 'age' => -5])); // false
    }
}
