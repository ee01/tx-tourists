<h1 align="center">TX Tourists</h1>


<p align="center">
  <a href="#features">Features</a> •
  <a href="#requirements">Requirements</a> •
  <a href="#installation">Installation</a>
</p>

## Features

- :dart: HTTP server request handlers and HTTP server middleware ([PSR-15](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-15-request-handlers.md))


## Requirements

- [PHP](https://www.php.net) 8.2 or newer
- [Composer](https://getcomposer.org)

## Installation
### Development Environment

Install Docker

Run `composer install` to intiate

Copy `.env.example` to `.env` and update the configurations inside

Manually upload `/sql` folder file to create database

Run `composer run serve` to start api server

(Visit http://localhost:8000/ for API)

Run `cd views & npm run serve` to start application

Visit http://localhost:8080/ for application

### Production Environment

Run `git clone git@github.com:ee01/tx-tourists.git` on server

Run `composer install` to intiate

Set http server to `public` folder

Copy `.env.example` to `.env` and update the configurations inside

run `sudo chmod -R 777 storage` to make sure permissions correct

Manually upload `/sql` folder file to create database

Add new webhook for deployment by Github push: https://github.com/ee01/webhook-eexx.me

Setup the push event as webhook to https://eexx.me/webhooks/api.tourists.ieexx.com.php on [Github](https://github.com/ee01/tx-tourists)->Setting->Webhooks

#### Multiple php versions server
For multiple php versions server, you may entercount the cli php version is low to install.

1. (done) Set sub-domain `api.xxx.com` to `public` folder, and set php version for this sub-domain to php 8.1 for web server.
2. Set sub-domain `api-cli.xxx.com` to root folder, and set php version for this sub-domain to php 8.1 for cli like `composer install`.

Deprecated:
1. Upload the whole vender folder to server manually instead of `composer install`.
2. Annotate the platform check in file `vendor/composer/platform_check.php` when running `composer xxxx`.

## Deploy

Run `git push` to deploy to eexx.me server automatically, as set the Github webhook.

That's it! Now go build something cool.
