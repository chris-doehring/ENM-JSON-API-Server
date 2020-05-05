JSON API Server
===============
[![Build Status](https://travis-ci.org/chris-doehring/ENM-JSON-API-Server.svg?branch=master)](https://travis-ci.org/chris-doehring/ENM-JSON-API-Server)

Abstract server-side php implementation of the [json api specification](http://jsonapi.org/format/).

It's based on the [original creation](https://github.com/eosnewmedia/JSON-API-Server) of the [eosnewmedia team](https://github.com/eosnewmedia) and the maintainer [Philipp Marien](https://github.com/pmarien).

## Installation

```sh
composer require chris-doehring/enm-json-api-server
```

## Documentation
First you should read the docs at [chris-doehring/enm-json-api-common](https://github.com/chris-doehring/ENM-JSON-API-Common/tree/master/docs) where all basic structures are defined.

1. [Json Api Server](docs/json-api-server/index.md)
    1. [Endpoints](docs/json-api-server/index.md#endpoints)
    1. [Usage](docs/json-api-server/index.md#usage)
1. [Request Handler](docs/request-handler/index.md)
    1. [Concept](docs/request-handler/index.md#concept)
    1. [Interface](docs/request-handler/index.md#interface)
    1. [Usage](docs/request-handler/index.md#usage)
1. [Exception handling](docs/exception-handling/index.md)

See [Change Log](CHANGELOG.md) for changes!
