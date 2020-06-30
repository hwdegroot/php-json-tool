[![pipeline status](https://gitlab.com/hwdegroot/php-json-tool/badges/master/pipeline.svg)](https://gitlab.com/hwdegroot/php-json-tool/commits/master)
[![coverage report](https://gitlab.com/hwdegroot/php-json-tool/badges/master/coverage.svg)](https://gitlab.com/hwdegroot/php-json-tool/commits/master)


An api to convert json to php associative arrays or export as CSV.

See [Examples](#endpoints) for more detail on the usage

Location of the repository [php-json-tool](https://gitlab.com/hwdegroot/php-json-tool)

## Getting started

### Clone

```
git clone git@gitlab.com:hwdegroot/php-json-tool.git

git clone https://gitlab.com/hwdegroot/php-json-tool.git
```

### Run

```
[PORT=8000] docker-compose up --build
```

Running at port 8000 (default)

### Lint

```
docker-compose run web lint-php.sh
```

### Test

```
docker-compose run web test
```

## Endpoints

### Health status

```
GET /api/v1/health

Response 200
```
`curl /api/v1/health`


### Nest flat structures

flatten a nested json or php file. if the `x-data-type` header is omitted the application will try to guess the type
The input file type will be automatically detected, snd the output filename will be determined by the extension that
is given in the request url



```
POST /api/v1/unflatten/flatfile.ext

DATA

file csv|php|json

RESPONSE

File with input nested

EXCEPTIONS

415 unsupported input format
415 unsupported ounput format
```

#### Examples

`curl /api/v1/unflatten/unflat.php -F "file=@path/to/flat.json"`
`curl /api/v1/unflatten/unflat.csv -F "file=@path/to/flat.php"`
`curl /api/v1/unflatten/unflat.json -F "file=@path/to/flat.php"`


### flatten nested structures

flatten a nested json or php file. if the `x-data-type` header is omitted the application will try to guess the type
The input file type will be automatically detected, snd the output filename will be determined by the extension that
is given in the request url

```
POST /api/v1/flatten/nestedfile.ext

DATA

file php|json

RESPONSE

File with flattened key value pairs

EXCEPTIONS

415 unsupported input format
415 unsupported ounput format
```

#### Examples

`curl /api/v1/unflatten/unflat.php -F "file=@path/to/nested.json"`
`curl /api/v1/unflatten/unflat.csv -F "file=@path/to/nested.php"`


### Conversion between types

Convert a filetype to another
The input file type will be automatically detected, snd the output filename will be determined by the extension that
is given in the request url

```
POST /api/v1/convert/convertedfile.ext

DATA

file json|php

RESPONSE

Converted file php|json

EXCEPTIONS

415 unsupported input format
415 unsupported ounput format
```


#### Examples

`curl /api/v1/convert -H "X-Output-Format: csv" -F "data=@path/to/file.json"`

