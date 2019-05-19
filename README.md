# Talesweaver
This is an application designed to help story writers to keep track of various
elements like scenes, characters, items or events.
## Installation
### Requirements
If you wish to start your own instance of the application, you will need:
1. [Composer](https://getcomposer.org/) + PHP 7.2+ along with extensions listed in [composer.json](./composer.json#L38)
2. [Yarn](https://yarnpkg.com/lang/en/) + node.js 10
3. [Webpack 4](https://webpack.js.org/)
4. MySQL 5.7
### Setup
#### Dependenices
```bash
composer.phar install
yarn install
```
#### Assets
Just run `webpack` or `node_modules/.bin/webpack` if you do not have a global Webpack installation.
#### Database
The application is build around the [Symfony](https://symfony.com/) framework and
has a concept of environments (for production, testing and development), more
[here](https://symfony.com/doc/current/configuration/environments.html). For each
 environment the application expects a different database to prevent data from
different ones mixing together. You can create each one with the following commands:
```bash
bin/console doctrine:database:create --env={one of: dev,test,test_cypress,prod}
bin/console doctrine:schema:create --env={one of: dev,test,test_cypress,prod}
# if you want to load test data
bin/console doctrine:fixtures:load -n --group=development --env={one of: dev,test,test_cypress,prod}
```
At this point you basically have a running setup. I will try to provide a `docker-compose.yml`
file sometime in the future to provide an out-of-the-box environment for development.
