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
docker-compose run web test
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

DATA

file csv|php|json

RESPONSE

File with input nested

EXCEPTIONS

415 unsupported input format
415 unsupported ounput format
```

#### Examples

`curl /api/unflatten/unflat.php -F "file=@path/to/flat.json"`

`curl /api/unflatten/unflat.csv -F "file=@path/to/flat.php"`

`curl /api/unflatten/unflat.json -F "file=@path/to/flat.php"`


### flatten nested structures

flatten a nested json or php file. if the `x-data-type` header is omitted the application will try to guess the type
The input file type will be automatically detected, snd the output filename will be determined by the extension that
is given in the request url

```
POST /api/flatten/nestedfile.ext

DATA

file php|json|csv

RESPONSE

File with flattened key value pairs

EXCEPTIONS

415 unsupported input format
415 unsupported ounput format
```

#### Examples

`curl /api/flatten/flat.php -F "file=@path/to/nested.json"`

`curl /api/flatten/flat.json -F "file=@path/to/nested.php"`

`curl /api/flatten/flat.csv -F "file=@path/to/nested.json"`

`curl /api/flatten/flat.csv -F "file=@path/to/nested.php"`


### Conversion between types

Convert a filetype to another
The input file type will be automatically detected, snd the output filename will be determined by the extension that
is given in the request url

```
POST /api/convert/convertedfile.ext

DATA

file json|php|csv

RESPONSE

Converted file php|json

EXCEPTIONS

415 unsupported input format
415 unsupported ounput format
400 unsupported conversion
```


#### Examples

`curl /api/convert/file.php -F "data=@path/to/file.json"`

`curl /api/convert/file.csv -F "data=@path/to/file.php"`

`curl /api/convert/file.json -F "data=@path/to/file.csv"`

