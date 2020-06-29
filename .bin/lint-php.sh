#!/bin/bash

echo ""
echo "----------------------------------"
echo "     Linting for syntax errors    "
echo "----------------------------------"
parallel-lint app

echo ""
echo "----------------------------------"
echo "      Linting code style          "
echo "----------------------------------"
rm .php_cs.cache
php-cs-fixer fix \
    --no-interaction \
    --show-progress=dots \
    -vvv

echo ""
echo "----------------------------------"
echo "      Detecting copy-paste        "
echo "----------------------------------"
phpcpd --fuzzy app

#echo ""
#echo "----------------------------------"
#echo "          Code style              "
#echo "----------------------------------"
# phpcs --colors --standard=PEAR app


echo ""
echo "----------------------------------"
echo "     Running Mess Destector       "
echo "----------------------------------"
phpmd app ansi codesize,controversial,design,naming,unusedcode

