<?php

namespace Hexlet\Validator\Tests;

use Hexlet\Validator\Validator;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

class FunctionsValidatorTest extends TestCase
{
    public function testFunctions(): void
    {
        $v = new Validator();
        $fn = fn($value, $start) => str_starts_with($value, $start);
        // Метод добавления новых валидаторов
        // addValidator($type, $name, $fn)
        $v->addValidator('string', 'startWith', $fn);

        // Новые валидаторы вызываются через метод test
        $schema = $v->string()->test('startWith', 'H');
        assertFalse($schema->isValid('exlet')); // false
        assertTrue($schema->isValid('Hexlet')); // true

        $fn = fn($value, $min) => $value >= $min;
        $v->addValidator('number', 'min', $fn);

        $schema = $v->number()->test('min', 5);
        assertFalse($schema->isValid(4)); // false
        assertTrue($schema->isValid(6)); // true

        // Если валидатора нет, то бросаем исключение
        try {
            $v->addValidator('wrong-name', 'startWith', $fn);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
