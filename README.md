## Code created for the TDD Tutorial with Laravel and PHPUnit 

[https://isaacsouza.dev/laravel-phpunit-tdd/](https://isaacsouza.dev/laravel-phpunit-tdd/)

## Installation instruction

1. Clone the repo and navigate to the directory
```
git clone git@github.com:isaac-souza/laravel-tdd.git
cd laravel-tdd
```
2. Copy the sample .env file
```
cp .env.example .env
```
3. Install the dependencies (requires at least PHP 8.0)
```
composer install
```
4. Start Laravel Sail (needs Docker installed in your system)
```
vendor/bin/sail up
```
5. Generate key
```
vendor/bin/sail artisan key:generate
```
6. Run the tests
```
vendor/bin/sail artisan test
```