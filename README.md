## Overview
A simple starter template of a SilverStripe project. This includes a basic .env.example with settings for a developer admin, a docker-compose to get started with a MySQL server, and a PSR-4 instruction for Composer to work with namespaced classes. Just remember to leave Page and PageController as is with their root namespace.

**To start** 
1. Run `composer install` to install all dependencies.
2. Run `docker-compose up -d` to launch a basic MySQL server.
3. Run `symfony serve -d` to launch a development server.
4. Go to `localhost:8000` to see the homepage. 
>On first visit the database is being build. It can exceed 30 seconds execution time, so make sure to update to `max_execution_time = 100` in `php.ini`.

### Miscellaneous notes
Base project folder for a SilverStripe ([http://silverstripe.org](http://silverstripe.org)) installation. Required modules are installed via [http://github.com/silverstripe/recipe-cms](http://github.com/silverstripe/recipe-cms). For information on how to change the dependencies in a recipe, please have a look at [https://github.com/silverstripe/recipe-plugin](https://github.com/silverstripe/recipe-plugin). In addition, installer includes [theme/simple](https://github.com/silverstripe-themes/silverstripe-simple) as a default theme.
