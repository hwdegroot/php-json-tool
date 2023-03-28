[![coverage report](https://gitlab.com/hwdegroot/php-json-tool/badges/main/coverage.svg)](https://gitlab.com/hwdegroot/php-json-tool/-/commits/main)
[![pipeline status](https://gitlab.com/hwdegroot/php-json-tool/badges/main/pipeline.svg)](https://gitlab.com/hwdegroot/php-json-tool/-/commits/main)

An api to convert between json files and php associative arrays or export as CSV.

See [Examples](#endpoints) for more detail on the usage

Location of the repository [php-json-tool](https://gitlab.com/hwdegroot/php-json-tool)

You can try it out [https://php-json-tool.herokuapp.com/](https://php-json-tool.herokuapp.com/)

Check out the current running version: [https://php-json-tool.herokuapp.com/api/health](https://php-json-tool.herokuapp.com/api/health)

## Getting started

### Clone

```
git clone git@gitlab.com:hwdegroot/php-json-tool.git

git clone https://gitlab.com/hwdegroot/php-json-tool.git
```

### Run

```
docker-compose up --build
```

Running at port 8080

### Lint

```
docker-compose run web lint
```

### Test

```
docker-compose run web pest
```

## Endpoints

### Health status

```
GET /api/health

Response 200
```
`curl /api/health`


### Nest flat structures

flatten a nested json or php file. if the `x-data-type` header is omitted the application will try to guess the type
The input file type will be automatically detected, snd the output filename will be determined by the extension that
is given in the request url



```
POST /api/unflatten/flatfile.ext

HEADERS

Input-Format: php|json|csv - used when the input file type can not be determined from the file

DATA

file csv|php|json
plain text php|json|csv  - requires Output-Format header to determine output filetype

RESPONSE

File with input nested

EXCEPTIONS

415 unsupported input format
415 unsupported ounput format
```

#### Examples

```sh
curl /api/unflatten/unflat.php -F file=@path/to/flat.json
curl /api/unflatten/unflat.php -d @path/to/flat.json -H"Input-Format: json" -H"Content-Type: application/json"

curl /api/unflatten/unflat.csv -F file=@path/to/flat.php
curl /api/unflatten/unflat.csv -d "file=@path/to/flat.php -H"Input-Format: php" -H"Content-Type: text/x-php"

curl /api/unflatten/unflat.json -F file=@path/to/flat.php
curl /api/unflatten/unflat.json -d @path/to/flat.php -H"Input-Format: php" -H"Content-Type: text/x-php"
```


### flatten nested structures

flatten a nested json or php file. if the `x-data-type` header is omitted the application will try to guess the type
The input file type will be automatically detected, snd the output filename will be determined by the extension that
is given in the request url

```
POST /api/flatten/nestedfile.ext

HEADERS

Input-Format: php|json|csv - used when the input file type can not be determined from the file

DATA

file php|json|csv
plain text php|json|csv  - requires Output-Format header to determine output filetype

RESPONSE

File with flattened key value pairs

EXCEPTIONS

415 unsupported input format
415 unsupported ounput format
```

#### Examples

```sh
curl /api/flatten/flat.php -F file=@path/to/nested.json
curl /api/flatten/flat.php -d @path/to/nested.json -H"Input-Format: json" -H"Content-Type: application/json"

curl /api/flatten/flat.json -F file=@path/to/nested.php
curl /api/flatten/flat.json -d @path/to/nested.php -H"Input-Format: php" -H"Content-Type: text/x-php"

curl /api/flatten/flat.csv -F file=@path/to/nested.json
curl /api/flatten/flat.csv -d @path/to/nested.json -H"Input-Format: json" -H"Content-Type: application/json"

curl /api/flatten/flat.csv -F file=@path/to/nested.php
curl /api/flatten/flat.csv -d @path/to/nested.php -H"Input-Format: php" -H"Content-Type: text/x-php"
```


### Conversion between types

Convert a filetype to another
The input file type will be automatically detected, snd the output filename will be determined by the extension that
is given in the request url

```
POST /api/convert/convertedfile.ext

HEADERS

Input-Format: php|json|csv - used when the input file type can not be determined from the file

DATA

file json|php|csv
plain text php|json|csv  - requires Output-Format header to determine output filetype

RESPONSE

Converted file php|json

EXCEPTIONS

415 unsupported input format
415 unsupported ounput format
400 unsupported conversion
```


#### Examples

```sh
curl /api/convert/file.php -F file=@path/to/file.json
curl /api/convert/file.php -d =@path/to/file.json -H"Input-Format: json" -H"Content-Type: application/json"

curl /api/convert/file.csv -F file=@path/to/file.php
curl /api/convert/file.csv -d @path/to/file.php -H"Input-Format: php" -H"Content-Type: text/x-php"

curl /api/convert/file.json -F file=@path/to/file.csv
curl /api/convert/file.json -d @path/to/file.csv -H"Input-Format: csv" -H"Content-Type: text/plain"
```

