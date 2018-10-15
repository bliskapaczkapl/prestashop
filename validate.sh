VENDOR_DIR=modules/bliskapaczka/vendor

docker run --rm -v $(pwd):/app -v ~/.composer:/tmp/composer -e COMPOSER_HOME=/tmp/composer -e COMPOSER_PROCESS_TIMEOUT=2000 composer/composer:php5 install

sudo chown -R $(id -u):$(id -g) .

$VENDOR_DIR/bin/security-checker security:check ./composer.lock
$VENDOR_DIR/bin/phpcs -s --colors --standard=rules.xml --ignore=modules/bliskapaczka/vendor/* modules/
$VENDOR_DIR/bin/phpmd modules/ text codesize --exclude modules/bliskapaczka/vendor,bliskapaczka/tests
$VENDOR_DIR/bin/phpcpd --exclude vendor modules/
$VENDOR_DIR/bin/phpdoccheck --directory=modules --exclude=bliskapaczka/tests,bliskapaczka/vendor
$VENDOR_DIR/bin/phploc modules/
$VENDOR_DIR/bin/phpunit --bootstrap modules/bliskapaczka/tests/bootstrap.php modules/bliskapaczka/tests/unit/

docker run --rm -u $(id -u):$(id -g) -v $(pwd):/app -v ~/.composer:/tmp/composer -e COMPOSER_HOME=/tmp/composer composer/composer:php5 install --no-dev