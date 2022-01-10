# A Developer’s Guide to Elasticsearch with Laravel

Code for A Developer’s Guide to Elasticsearch with Laravel and Okta.

## Installation

After cloning the repository run the following command to install dependencies:

```bash
composer install
```

## Configurations

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

And add values for the following keys in `.env`:

```
OKTA_BASE_URL=
OKTA_CLIENT_ID=
OKTA_CLIENT_SECRET=
OKTA_REDIRECT_URI=

ELASTICSEARCH_HOST=127.0.0.1
ELASTICSEARCH_PORT=9200
ELASTICSEARCH_SCHEME=http
ELASTICSEARCH_USER=
ELASTICSEARCH_PASS=
```

## Prepare Database

Create the SQLite database:

```bash
touch database/db.sqlite
```

The migrate the changes:

```bash
php artisan migrate
```

## Run the Server

Run the following command to start the server:

```bash
php artisan serve
```
