[![Actions Status](https://github.com/petrdobr/php-oop-project-60/workflows/hexlet-check/badge.svg)](https://github.com/petrdobr/php-oop-project-60/actions)

## Data validation Library
[Click here to read russian version](READMErus.md).

This library is created as an educational project on Hexlet platform. [See the description of the project (rus)](https://ru.hexlet.io/programs/php-oop/projects/60).

It is a data validator that can check data to be of certain type with some other checking methods defined. 

## Usage and description
### General notes
First you need to create an object `Validator()`.
```php
$v = new \Hexlet\Validator\Validator();
```

Checking is performed first by stating the type for validation, then by calling methods specifying a certain types of checking needed to be performed (these are not obligatory) and then by passing in data using `isValid($data)` method.
```php
$schema = $v->string()->contains('hey')->isValid('hey there');
```


Methods defined in this library for different data type validation:

`string()` - for string type data; 

`number()` - for integer type data; 

`array()` - for array type data; 

`addValidator(string $type, string $name, callable $fn)` - add custom validator function `$fn` to a `$type` validator;


Calling same method creates different schema for validation (no need to create new `Validator()` objects for every schema).
```php
$v = new \Hexlet\Validator\Validator();

$schema = $v->string();
$schema2 = $v->string(); // $schema != $schema2
```


> [!NOTE]
> All called methods listed below for different data types are stackable. For example calling positive and range methods for number type  will exclude negative numbers from the range:
```
$schema->number()->positive()->range(-5, 5);

$schema->isValid(-3); // false
$schema->isValid(5); // true
```
### String validation methods
`required()` method makes data not nullable. Call this method before passing in data.
```php
$v = new \Hexlet\Validator\Validator();
$schema = $v->string();
$schema->isValid(null); // true

$schema->required();

$schema->isValid(null); // false
$schema->isValid(''); // false
```
`contains($string)` - checks for `$string` to be present in a `$data` passed in with `isValid($data)` method:
```php
$schema->contains('what')->isValid('what does the fox say'); // true
$schema->contains('whatthe')->isValid('what does the fox say'); // false
```
`minLength($num)` - checks `$data` to be at least `$num` characters long:
```php
$v->string()->minLength(5)->isValid('Hexlet'); // true
```
### Number validation methods
Works with integers.

`required()` method makes data not nullable. 0 (zero) is a number too, `required()` will return true.
```php
$v = new \Hexlet\Validator\Validator();

$schema = $v->number();
$schema->isValid(null); // true
$schema->required();
$schema->isValid(null); // false
$schema->isValid(0); // true
```
`positive()` - checks if number is above 0. (0 included)
```php
$schema->positive()->isValid(10); // true
```
`range($left, $right)` - specify range in which the checked number should be located. Boundaries are included.
```php
$schema->range(-5, 5);
$schema->isValid(5); // true
```
### Array validation methods
`required()` method makes data not nullable. Empty array is considered a valid data, returns true after check
```php
$schema = $v->array();
$schema->isValid(null); // true

$schema = $schema->required();

$schema->isValid([]); // true
$schema->isValid(['hexlet']); // true
```
`sizeOf($num)` - checks if the length of an array is exactly `$num`
```php
$schema->sizeof(2);

$schema->isValid(['hexlet']); // false
$schema->isValid(['hexlet', 'code-basics']); // true
```
`shape()` - with this method you can specify what data type and checks should be performed on data under the certain key-values of an array:
```php
$schema->shape([
    'name' => $v->string()->required(),
    'age' => $v->number()->positive(),
]);

$schema->isValid(['name' => 'kolya', 'age' => 100]); // true
$schema->isValid(['name' => 'maya', 'age' => null]); // true
$schema->isValid(['name' => '', 'age' => null]); // false
$schema->isValid(['name' => 'ada', 'age' => -5]); // false
```
### Create custom validation rules
`addValidator(string $type, string $name, callable $fn)` method can add a custom validator function `$fn` to a `$type` validator. Types are passed in as a string and 3 types (string, number, array) are supported.
```php
$v = new \Hexlet\Validator\Validator();

$fn = fn($value, $start) => str_starts_with($value, $start);
$v->addValidator('string', 'startWith', $fn);
```
`test()` method should be called to initiate data validation by the rules added.
```php
$schema = $v->string()->test('startWith', 'H');
$schema->isValid('exlet'); // false
$schema->isValid('Hexlet'); // true
```
Another example:
```php
$fn = fn($value, $min) => $value >= $min;
$v->addValidator('number', 'min', $fn);

$schema = $v->number()->test('min', 5);
$schema->isValid(4); // false
$schema->isValid(6); // true
```
---
More documentation makes the world a better place.

**Thank you for reading!**
