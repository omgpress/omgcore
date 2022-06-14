# Wpappy
<a href="https://packagist.org/packages/wpappy/wpappy"><img src="https://img.shields.io/packagist/v/wpappy/wpappy" alt="Packagist version"/></a>

The library that introduces a smart layer between the WordPress environment and your plugin or theme (hereinafter referred to as the application).

- [System Requirements](#system-requirements)
- [Installation](#installation)
- [Documentation](#documentation)
- [Examples](#examples)
- [License](#license)

## System Requirements
- PHP: ^7.2.0
- WordPress: ^5.0.0
- Composer (optional)

## Installation
Run the following command in root directory of your application to install using Composer:\
`composer require wpappy/wpappy`.\
Alternatively, you can [download the latest release](https://github.com/wpappy/wpappy/releases/latest) directly and place unarchived folder in root directory of the application.

## Documentation
The latest documentation is published on [wpappy.dpripa.com](https://wpappy.dpripa.com).\
For convenience, it's better to start from [the entry point](https://wpappy.dpripa.com/classes/Wpappy-1-0-0-App.html) of the library.

If you need documentation for previous versions, follow these instructions:
- Install [phpDocumentor](https://www.phpdoc.org) into your system.
- Download the version you need from [the release list](https://github.com/wpappy/wpappy/releases) and unzip the downloaded archive.
- Run `phpDocumentor` command in the unarchived folder.
- After phpDocumentor has reported success, you can find the generated documentation in the `docs/api` directory.

## Examples
Here are examples of using the same Wpappy based code in different environments.\
Also, the following template repositories are the best starting point to start developing a new WordPress application:
- [Starter Plugin](https://github.com/wpappy/wpappy-starter-plugin)
- [Starter Theme](https://github.com/wpappy/wpappy-starter-theme)

## License
Wpappy is free library (software), and is released under the terms of the GPL (GNU General Public License) version 2 or (at your option) any later version. See [LICENSE](https://github.com/wpappy/wpappy/blob/main/LICENSE).
