<?php

namespace Hexlet\Validator\Tests;

use Hexlet\Validator\Validator;
use PHPUnit\Framework\TestCase;

class StringValidatorTest extends TestCase
{
    public function testString(): void
    {
        $v = new Validator();
        $schema = $v->string();
        $schema2 = $v->string(); // $schema != $schema2
        $schema3 = $v->string();

        $this->assertNotEquals($schema, $schema2);
        $this->assertNotEquals($schema, $schema3);
        $this->assertTrue($schema->isValid(null));
        $this->assertTrue($schema->isValid('')); //!!!

        $schema->required();
        $this->assertFalse($schema->isValid('')); //!!!
        $this->assertFalse($schema->isValid(1234));
        $this->assertTrue($schema2->isValid(null));
        $this->assertFalse($schema->isValid(null));

        $this->assertTrue($schema->isValid('hexlet'));
        $schema->required();
        $schema->minLength(5);
        $this->assertTrue($schema->isValid('hexlet'));
        $this->assertTrue($schema->isValid('hexlethexlet'));
        $this->assertFalse($schema->isValid('hex'));

        $schema->minLength(4)->minLength(5);
        $this->assertTrue($schema->isValid('hexlet'));
        $this->assertFalse($schema->isValid('hex'));

        $schema->contains('what');
        $this->assertTrue($schema->isValid('what does the fox say'));
        $schema->contains('whats');
        $this->assertFalse($schema->isValid('what does the fox say'));

        $this->assertTrue($v->string()->minLength(10)->minLength(5)->isValid('Hexlet'));
    }
}
