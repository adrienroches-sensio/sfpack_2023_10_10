Requirements
============

1. [symfony-cli](https://symfony.com/download)
2. PHP >= 8.2
3. pdo_sqlite
4. composer
5. yarn

Install
=======

1. clone the repository
2. Fetch an API Key from https://www.omdbapi.com/apikey.aspx
3. create a `.env.local` at the root of the project with teh following content:
```dotenv
OMDB_API_KEY="API_KEY_FROM_OMDB_WEBSITE"
```
4. Run the following commands :

```bash
$ symfony composer install
$ yarn install
$ yarn dev
$ symfony console doctrine:migrations:migrate -n
$ symfony console doctrine:fixtures:load -n
$ symfony serve -d
```

Importing movies
================

```bash
$ symfony console app:movies:import tt1298554 "harry potter" "spread your wings" "Hidden Figures" tt123456
```

Use the following to try the command without importing :
```bash
$ symfony console app:movies:import tt1298554 "harry potter" "spread your wings" "Hidden Figures" tt123456 --dry-run
```

Log in
======

1. `adrien`/`adrien` [admin]
2. `max`/`max` [admin]
3. `lou`/`lou`
4. `john`/`john`
