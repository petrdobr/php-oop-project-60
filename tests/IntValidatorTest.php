<?php

namespace Hexlet\Validator\Tests;

use Hexlet\Validator\Validator;
use PHPUnit\Framework\TestCase;

class IntValidatorTest extends TestCase
{
    public function testInt(): void
    {
        $v = new Validator();
        $schema = $v->number();
        $schema2 = $v->number(); // $schema != $schema2
        $schema3 = $v->number();

        $this->assertNotEquals($schema, $schema2);
        $this->assertNotEquals($schema, $schema3);
        $this->assertTrue($schema->isValid(null));
        $this->assertTrue($schema->isValid(null));

        //schema3 is not required
        $this->assertTrue($schema3->isValid(null));
        $this->assertTrue($schema3->isValid(42));
        $this->assertTrue($schema3->positive()->isValid(42));
        $this->assertFalse($schema3->positive()->isValid(-42));
        $this->assertTrue($schema3->positive()->isValid(null));

        $schema->required();
        $this->assertTrue($schema->isValid(42));
        $this->assertFalse($schema->isValid('42'));
        $this->assertTrue($schema2->isValid(null));
        $this->assertFalse($schema->isValid(null));

        $this->assertTrue($schema->isValid(9999999));
        $this->assertTrue($schema->positive()->isValid(42));
        $this->assertFalse($schema->isValid(-42));

        $schema2->range(-5, 5);
        $this->assertTrue($schema2->isValid(1));
        $this->assertTrue($schema2->isValid(-1));
        $this->assertFalse($schema->isValid(-10));

        $schema->range(-5, 5);
        $this->assertTrue($schema->isValid(1));
        $this->assertFalse($schema->isValid(-1));
        $this->assertFalse($schema->isValid(-10));
    }
}
