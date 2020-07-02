# Serpro Datavalid package for Laravel

This package makes it easy to use Serpro [Datavalid](https://servicos.serpro.gov.br/datavalid/) API with Laravel framework.

## Contents

- [Installation](#installation)

- [Usage](#usage)

- [Changelog](#changelog)

- [Testing](#testing)

- [Security](#security)

- [Contributing](#contributing)

- [Credits](#credits)

- [License](#license)

## Installation

This package can be installed via composer:

`composer require lucasgiovanny/laravel-serpro-datavalid`

## Usage

To use this package, you just need to import the Person Facades.

```php
use  lucasgiovanny\SerproDataValid\Person;
```

### Available methods

 - [`rawValidation`](#rawValidation): Make a raw validation with any data you need to validate according to [Datavalid API Docs](https://apidocs.datavalidp.estaleiro.serpro.gov.br).
 - [`validateName`](#validateName): Returns whether the name belongs to the CPF and its rate of assertiveness.
 - [`validateGender`](#validateGender): Returns whether the CPF has this gender.
 - [`isBrazilian`](#isBrazilian): Returns whether the person to whom this CPF belongs is Brazilian or not.
 - [`validateParentsName`](#validateParentsName): Returns whether the parents name belongs to the CPF and its rate of assertiveness.
 - [`isCPFRegular`](#isCPFRegular): Returns if CPF is regular with Brazilian government.
 - [`validatePhoto`](#validatePhoto): Returns if the person in the photo is the person that owns this CPF number.

#### rawValidation

|Param| Type |
|--|--|
|cpf|*string* (**required**)|
|answers|*array* (**required**)|

Example:

```php
use  lucasgiovanny\SerproDataValid\Person;

$data = [
'nome'  =>  "João da Silva",
'sexo' => 'M'
'situacao_cpf'  =>  'regular',
];

$validation = Person::rawValidation("00000000000", $data);
```

*Please, see the [Data Valid API docs](https://apidocs.datavalidp.estaleiro.serpro.gov.br) for a list of all the propriety that can be checked.*

#### validateName

|Param| Type |
|--|--|
|cpf|*string* (**required**)|
|name|*string* (**required**)|
|getSimilarity|*bool* (default: false)|

Example:

```php
use  lucasgiovanny\SerproDataValid\Person;

$validation = Person::validateName("00000000000", "João da Silva");
//return true or false;
$validation = Person::validateName("00000000000", "João da Silva", true);
//return an object, like:
	// $validation->nome = true;
	// $validation->nome_similaridade = 0.99
```

#### validateGender

|Param| Type |
|--|--|
|cpf|*string* (**required**)|
|gender|*string* (**required**)|

Example:

```php
use  lucasgiovanny\SerproDataValid\Person;

$validation = Person::validateGender("00000000000", "F"); // gender needs to be "F" or "M"
//return true or false;
```

#### isBrazilian

|Param| Type |
|--|--|
|cpf|*string* (**required**)|

Example:

```php
use  lucasgiovanny\SerproDataValid\Person;

$validation = Person::isBrazilian("00000000000");
//return true or false;
```

#### validateParentsName

|Param| Type |
|--|--|
|cpf|*string* (**required**)|
|parents|*array* (**required**)|
|getSimilarity|*bool* (default: false)|

Example:

```php
use  lucasgiovanny\SerproDataValid\Person;

$parents = [
	'mother_name' => 'Eurica Magalhães Souza';
	'father_name' => 'Frederico Fagundes Souza';
]; // you can check just one of the names

$validation = Person::validateParentsName("00000000000", $parents);
//return an object with "mother_name" and "father_name" true or false values;

$validation = Person::validateParentsName("00000000000", $parents, true);
//return an object with "mother_name" and "father_name" true or false values, 
//and "mother_name_similarity" and "father_name_similarity" numbers,
//just like in validateName method.
```

#### isCPFRegular

|Param| Type |
|--|--|
|cpf|*string* (**required**)|

Example:

```php
use  lucasgiovanny\SerproDataValid\Person;

$validation = Person::isCPFRegular("00000000000");
//return true or false;
```

#### validatePhoto

|Param| Type |
|--|--|
|cpf|*string* (**required**)|
|photo|*string* (**required**)

Example:

```php
use  lucasgiovanny\SerproDataValid\Person;

$validation = Person::validatePhoto("00000000000", base64_encode($photo));
//return true or false;
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

Test needs to be written. Feel free to collaborate.

## Security

If you discover any security related issues, please email lucasgiovanny@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Lucas Giovanny](https://github.com/lucasgiovanny)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.