#!/bin/sh

DIR=$( cd "$( dirname "$0" )" && pwd )
ROOT="$DIR/../.."
php "$ROOT/vendor/bin/lattecs" "$ROOT/app/" && php "$ROOT/vendor/bin/phpcs" -p --standard=tests/cs "$ROOT/app/"
