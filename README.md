## About the Task Management application
This is simple tasks management (todos) application and requires basic registration to access its features:
- user registration, login, and profile management;
- dashboard (future dev: news, reminders, announcements, ... etc);
- tasks module with ability to create/list/update/delete;

## Requirements
 - PHP >= 8.2 

## Basic setup steps
```
# unpack to ./example-app
cd example-app
composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
./vendor/bin/sail npm install
./vendor/bin/sail npm run build

# bring stack down 
./vendor/bin/sail down
```

## Running tests (feature & unit)
```
./vendor/bin/sail artisan test

or

./vendor/bin/sail test
```

## Accessing App
URL: http://localhost - as default Laravel based project. Simply register with any user details to access feature.
