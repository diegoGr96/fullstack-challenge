## Steps to setup project

This challenge has been made with a local server like Xampp, Wampp, etc.
In order to setup the project you should follow the next steps:

- Copy .env.example file and rename it to .env. This file already has sample Database credentials.
- Run `composer install --prefer-dist` (use `--prefer-dist` to create a non-"version control repository").
- Run `composer dump-autoload` (ensure custom Src namespace is loaded).
- Run `php artisan key:generate`
- Create your local Database (you can use the sample credentials already written in the .env file or change them).
- Run `php artisan migrate`
- You're done! Now the project is ready to use it.

# About the project
The project has been created following TDD, Hexagonal Arquitecture, DDD, SOLID Principles and using Dessing Patterns with the goal of creating a scalable and testable application.

## How to run tests
- Run all tests: php artisan test
- Run Unit tests: php artisan test --testsuite=Unit
- Run Feature tests: php artisan test --testsuite=Feature

## Dessing Patterns

- Result pattern: Allow normalizing both responses and errors of the methods and proccesses in the project.
- Value Object: Creates Classes that encapsulates the logic about a value with the advantatge of ensuring the correct use, validation and creation of it.
- Factory "idiom": Used in some Value Objects, entities and aggregates in order to centralice and ensure a properly creation of the instances.
- Repository pattern: Allows to centralice and manage the "data access" parts of the project.
- Strategy Pattern: Allows you to choose between different solutions to a problem at runtime.


## Extra

I've created a Presenter with the objective of encapsulating the responses presentation logic.

This allows to easily create multiple types of presentations by creating new Presenters like "CliPresenter", "WebPresenter", etc


I have created a simulation of a domain event manager that I would use in a real project. You can find the relevant code in the following files:

- `src/Shared/Domain/Entities/Entity.php`
- `src/Shared/Domain/Events/DomainEventsPublisher.php`
- `app/Providers/AppServiceProvider.php`
- `src/Shared/Infrastructure/Events/LaravelDomainEventsPublisher.php`
- `app/Jobs/PublishDomainEvent.php`