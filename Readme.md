Globalis Technical test
===

You have a makefile to simplify the process (sorry, Windows don't have make).

### With the Makefile
1. Tweak the `docker-compose.override.yml` in the `docker` folder if you want
2. Run `make install`
3. Run `make execute`
4. Enjoy the application and the lack of optimization :)

### Without the Makefile
1. Tweak the `docker-compose.override.yml` in the `docker` folder if you want
2. Copy and paste `.env.dist` then rename it `.env`, same with `.php.env.dist`
3. Run `docker-compose -p globalis -f docker/docker-compose.yml -f docker/docker-compose.override.yml up --remove-orphans -d`
4. Run `docker-compose -p globalis -f docker/docker-compose.yml -f docker/docker-compose.override.yml exec --user=application php composer install --no-suggest --no-progress`
5. Run `docker-compose -p globalis -f docker/docker-compose.yml -f docker/docker-compose.override.yml exec --user=application php php public/index.php`
6. Enjoy the application and the lack of optimization :)

## Unit tests
### Without coverage
1. Enter into the container with `make bash` (or `docker-compose -p globalis -f docker/docker-compose.yml -f docker/docker-compose.override.yml exec --user=application php bash`)
2. Run `/app/vendor/bin/phpunit --whitelist /app/src --no-configuration /app/tests`

### With coverage
1. Make sure that you use `webdevops/php-dev:8.0-alpine` in your `docker-compose.override.yml`
2. Enter into the container with `make bash` (or `docker-compose -p globalis -f docker/docker-compose.yml -f docker/docker-compose.override.yml exec --user=application php bash`)
3. Run `/app/vendor/bin/phpunit --configuration /app/phpunit.xml.dist /app/tests`
4. The coverage should be generated in `coverage/`
  
Note that `Xdebug: [Config]` lines are due to the container: [Pull request resolving this](https://github.com/webdevops/Dockerfile/pull/386)

### Infection testing
1. Requires the coverage
2. Enter into the container with `make bash` (or `docker-compose -p globalis -f docker/docker-compose.yml -f docker/docker-compose.override.yml exec --user=application php bash`)
3. Run `vendor/bin/infection --coverage=coverage`
4. The infection files should be generated into the `coverage/` folder as well
