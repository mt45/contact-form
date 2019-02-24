### RESTful API

#### Quick start

Run
```bash
bash init.sh
```

#### Setting by steps

Run
```bash
composer install
```

Update `.env`

Create database
```bash
php bin/console doctrine:database:create
```

Create tables
```bash
php bin/console doctrine:schema:create
```

Create database and tables for test environment
```bash
php bin/console doctrine:database:create --env=test
php bin/console doctrine:schema:create --env=test
```

#### Checking API doc

***$api_url***`/api/doc`

#### Running tests

All
```bash
php vendor/bin/codecept run
```

Api
```bash
php vendor/bin/codecept run api
```

Unit
```bash
php vendor/bin/codecept run unit
```
