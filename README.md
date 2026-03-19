# Acorn Mail

![Latest Stable Version](https://img.shields.io/packagist/v/roots/acorn-mail.svg?style=flat-square)
[![Packagist Downloads](https://img.shields.io/packagist/dt/roots/acorn-mail?label=downloads&colorB=2b3072&colorA=525ddc&style=flat-square)](https://packagist.org/packages/roots/acorn-mail)
![Build Status](https://img.shields.io/github/actions/workflow/status/roots/acorn-mail/main.yml?branch=main&style=flat-square)
[![Follow Roots](https://img.shields.io/badge/follow%20@rootswp-1da1f2?logo=twitter&logoColor=ffffff&message=&style=flat-square)](https://twitter.com/rootswp)
[![Sponsor Roots](https://img.shields.io/badge/sponsor%20roots-525ddc?logo=github&style=flat-square&logoColor=ffffff&message=)](https://github.com/sponsors/roots)

Acorn Mail is a simple package handling WordPress SMTP using Acorn's mail configuration.

## Support us

Roots is an independent open source org, supported only by developers like you. Your sponsorship funds [WP Packages](https://wp-packages.org/) and the entire Roots ecosystem, and keeps them independent. Support us by purchasing [Radicle](https://roots.io/radicle/) or [sponsoring us on GitHub](https://github.com/sponsors/roots) — sponsors get access to our private Discord.

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

For most configurations, you can simply set the following variables in your environment:

```env
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME=
```

If you need to change the default encryption from `tls`, you can set the encryption variable:

```env
MAIL_ENCRYPTION=ssl
```

Once the credentials are properly configured, you can send a test email using Acorn's CLI:

```sh
$ wp acorn mail:test [--to=] [--subject=] [--body=]
```

You can customize the test email subject and body:

```sh
# Test with custom subject and body
$ wp acorn mail:test --subject="Deploy Test" --body="Deployment successful"

# Test with custom recipient
$ wp acorn mail:test --to=admin@example.com
```

If any errors are detected, they will be printed to console.

## Community

Keep track of development and community news.

- Join us on Discord by [sponsoring us on GitHub](https://github.com/sponsors/roots)
- Join us on [Roots Discourse](https://discourse.roots.io/)
- Follow [@rootswp on Twitter](https://twitter.com/rootswp)
- Follow the [Roots Blog](https://roots.io/blog/)
- Subscribe to the [Roots Newsletter](https://roots.io/subscribe/)
