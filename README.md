## Basic setup steps
```
# unpack to ./example-app
cd example-app
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
```

## Accessing App
URL: http://localhost - as default Laravel based project. Simply register with any user details to access feature.
