#!/bin/sh

java -jar swagger-codegen-cli.jar generate -i  https://app.doofinder.com/api/v2/swagger.json -c conf.json -l php -o . &&
find ./Management -type f -exec sed -i -e 's;=> '"'"'OneOf;=> '"'"'\\DoofinderManagement\\Model\\OneOf;g' {} \;
