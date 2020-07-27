JSON API Server
===============
[![Build Status](https://travis-ci.org/chris-doehring/ENM-JSON-API-Server.svg?branch=5.x)](https://travis-ci.org/chris-doehring/ENM-JSON-API-Server)
[![Coverage Status](https://coveralls.io/repos/github/chris-doehring/ENM-JSON-API-Server/badge.svg?branch=5.x)](https://coveralls.io/github/chris-doehring/ENM-JSON-API-Server?branch=5.x)
[![Total Downloads](https://poser.pugx.org/chris-doehring/enm-json-api-server/downloads)](https://packagist.org/packages/chris-doehring/enm-json-api-server)
[![Latest Stable Version](https://poser.pugx.org/chris-doehring/enm-json-api-server/v/stable)](https://packagist.org/packages/chris-doehring/enm-json-api-server)
[![Latest Unstable Version](https://poser.pugx.org/chris-doehring/enm-json-api-server/v/unstable.png)](https://packagist.org/packages/chris-doehring/enm-json-api-server)
[![License](https://poser.pugx.org/chris-doehring/enm-json-api-server/license)](https://packagist.org/packages/chris-doehring/enm-json-api-server)

Abstract server-side php implementation of the [json api specification](http://jsonapi.org/format/).

It's based on the [original creation](https://github.com/eosnewmedia/JSON-API-Server) of the [eosnewmedia team](https://github.com/eosnewmedia) and the maintainer [Philipp Marien](https://github.com/pmarien).

## Installation

```sh
composer require chris-doehring/enm-json-api-server
```

## Documentation
First you should read the docs at [chris-doehring/enm-json-api-common](https://github.com/chris-doehring/ENM-JSON-API-Common/tree/5.x/docs) where all basic structures are defined.

1. [Json Api Server](docs/json-api-server/index.md)
    1. [Endpoints](docs/json-api-server/index.md#endpoints)
    1. [Usage](docs/json-api-server/index.md#usage)
1. [Request Handler](docs/request-handler/index.md)
    1. [Concept](docs/request-handler/index.md#concept)
    1. [Interface](docs/request-handler/index.md#interface)
    1. [Usage](docs/request-handler/index.md#usage)
1. [Exception handling](docs/exception-handling/index.md)

See [change log](CHANGELOG.md) for changes!
