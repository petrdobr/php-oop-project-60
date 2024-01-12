## Библиотека валидатор данных
[Здесь версия на английском](README.md).

Эта библиотека создана в рамках образовательного проекта на платформе Хекслет. [Посмотреть описание проекта](https://ru.hexlet.io/programs/php-oop/projects/60).

Библиотека представляет собой валидатор, который проверяет данные на принадлежность определенному типу и предоставляет несколько отдельных методов для проверки.

## Использование и описание
### Общие замечания
Проверка осуществляется с помощью объекта `Validator()`.
```php
$v = new \Hexlet\Validator\Validator();
```
Проверка производится сперва указанием типа для валидации, затем возможен вызов отдельных функций, производящих дополнительные проверки, после чего вызывается функция `isValid($data)`, передающая данные для проверки.
```php
$schema = $v->string()->contains('hey')->isValid('hey there');
```

Реализованы следующие методы, позволяющие проверить разные типы данных:

`string()` - для строковых данных; 

`number()` - для целочисленных данных; 

`array()` - для массивов; 

`addValidator(string $type, string $name, callable $fn)` - метод позволяет добавить свое правило, заданное функцией `$fn`, к валидатору данных типа `$type`;

Вызов одинаковых методов создает разные схемы валидации. Это сделано, чтобы не вызывать каждый раз объект `Validator()` для разных схем проверки.
```php
$v = new \Hexlet\Validator\Validator();

$schema = $v->string();
$schema2 = $v->string(); // $schema != $schema2
```


> [!NOTE]
> Все методы, перечисленные ниже для разных типов данных могут вызываться друг за другом. Такой вызов позволяет применять эти проверки одновременно. Например, метод для проверки положительного числа и вхождения его в некоторый числовой диапазон сократит диапазон проверки, исключив из него отрицательные числа:
```php
$schema->number()->positive()->range(-5, 5);

$schema->isValid(-3); // false
$schema->isValid(5); // true
```
### Методы валидации строк
`required()` - делает проверку обязательной. Пустая строка не пройдет проверку.
```php
$v = new \Hexlet\Validator\Validator();
$schema = $v->string();
$schema->isValid(null); // true

$schema->required();

$schema->isValid(null); // false
$schema->isValid(''); // false
```
`contains($string)` - проверяет, что строка `$string` присутствует в данных `$data` хотя бы раз:
```php
$schema->contains('what')->isValid('what does the fox say'); // true
$schema->contains('whatthe')->isValid('what does the fox say'); // false
```
`minLength($num)` - проверяет, что строка `$data` длиной не менее `$num`:
```php
$v->string()->minLength(5)->isValid('Hexlet'); // true
```
### Методы валидации чисел
Реализовано для целых чисел (integers).

`required()` - делает проверку обязательной. 0 (ноль) тоже считается числом, `required()` вернет `true`.
```php
$v = new \Hexlet\Validator\Validator();

$schema = $v->number();
$schema->isValid(null); // true
$schema->required();
$schema->isValid(null); // false
$schema->isValid(0); // true
```
`positive()` - проверяет, что число положительно (0 включиельно).
```php
$schema->positive()->isValid(10); // true
```
`range($left, $right)` - определяет диапазон, в котором должно находится проверяемое число (включая границы).
```php
$schema->range(-5, 5);
$schema->isValid(5); // true
```
### Методы проверки массивов
`required()` - делает проверку обязательной. Пустой массив вернет `true`.
```php
$schema = $v->array();
$schema->isValid(null); // true

$schema = $schema->required();

$schema->isValid([]); // true
$schema->isValid(['hexlet']); // true
```
`sizeOf($num)` - проверяет, что длина массива равна `$num`
```php
$schema->sizeof(2);

$schema->isValid(['hexlet']); // false
$schema->isValid(['hexlet', 'code-basics']); // true
```
`shape()` - с помощью этого метода можно задать параметры проверки значений, принадлежащих определенному ключу:
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
### Создание собственных правил проверки
`addValidator(string $type, string $name, callable $fn)` - позволяет добавить собственную функцию `$fn` для проверки данных типа `$type`. Поддерживается 3 типа данных для проверки: (string, number, array). Передавать имя метода следует в виде строки.
```php
$v = new \Hexlet\Validator\Validator();

$fn = fn($value, $start) => str_starts_with($value, $start);
$v->addValidator('string', 'startWith', $fn);
```
`test()` - запускает проверку данных.
```php
$schema = $v->string()->test('startWith', 'H');
$schema->isValid('exlet'); // false
$schema->isValid('Hexlet'); // true
```
Другой пример:
```php
$fn = fn($value, $min) => $value >= $min;
$v->addValidator('number', 'min', $fn);

$schema = $v->number()->test('min', 5);
$schema->isValid(4); // false
$schema->isValid(6); // true
```
---
Жить здорово. Всего вам доброго :)

**Спасибо за внимание!**
