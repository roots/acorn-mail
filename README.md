# Acorn Mail

![Latest Stable Version](https://img.shields.io/packagist/v/roots/acorn-mail.svg?style=flat-square)
![Total Downloads](https://img.shields.io/packagist/dt/roots/acorn-mail.svg?style=flat-square)
![Build Status](https://img.shields.io/github/actions/workflow/status/roots/acorn-mail/main.yml?branch=main&style=flat-square)

Acorn Mail is a simple package handling WordPress SMTP using Acorn's mail configuration.

## Requirements

- [PHP](https://secure.php.net/manual/en/install.php) >= 8.1
- [Acorn](https://github.com/roots/acorn) >= 3.0

## Installation

Install via Composer:

```sh
$ composer require roots/acorn-mail
```

## Getting Started

Start by optionally publishing Acorn's mail config:

```sh
$ wp acorn mail:config
```

SMTP credentials can be found in the published `mail.php` config file and default to environment variables.

## Usage

For most configurations, you can simply set the following environment variables:

```env
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME=
```

Once the credentials are properly configured, you can send a test email using Acorn's CLI:

```sh
$ wp acorn mail:test [--to=]
```

If any errors are detected, they will be printed to console.

## Bug Reports

If you discover a bug in Acorn Mail, please [open an issue](https://github.com/roots/acorn-mail/issues).

## Contributing

Contributing whether it be through PRs, reporting an issue, or suggesting an idea is encouraged and appreciated.

## License

Acorn Mail is provided under the [MIT License](LICENSE.md).
