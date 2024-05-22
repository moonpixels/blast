# Blast

[![Tests](https://github.com/moonpixels/blast/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/moonpixels/blast/actions/workflows/tests.yml)

Blast is a privacy focused link shortener that's built for developers. It's open source, and you can host it yourself if
you wish.

## Introduction

Version 2 of Blast is a complete rewrite of the original. It's easier to use, and with a solid foundation, it's more
extensible than ever. It's built with [Laravel](https://laravel.com) and [Vue.js](https://vuejs.org).

## Installation

You can install Blast in a few simple steps, this applies to both self-hosting and contributing to the project.

1. Clone the repository: `git clone https://github.com/moonpixels/blast.git`
2. Copy `.env.example` to `.env` and fill in the required values
3. Install dependencies: `composer install && npm install && npm run prod`
4. Generate app key: `php artisan key:generate`
5. Run database migrations: `php artisan migrate`

## Contributing

Contributions are welcome. We've built Blast for developers, so if there's a feature you'd like to see, or a bug you'd
like to fix, please open a pull request.

Local development is easy with [Laravel Sail](https://laravel.com/docs/10.x/sail). Once you've cloned the repository,
just run `sail up -d` to start the development environment. You can then access the site at `localhost`. We've included
a `.env.dev` file that you can use to get started.

## License

Blast is open-sourced software licensed under the [MIT license](LICENSE.md).
