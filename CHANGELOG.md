# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
### Removed
- **BC break**: Removed support for PHP versions <= 8.0 as they are no longer
  [actively supported](https://php.net/supported-versions.php)
  by the PHP project.

## [2.0.4] - 2021-05-30
### Added
- Add specific support for PHP v8

## [2.0.3] - 2017-10-30

- Fixed issue #4 overriding a non array element in the destination array

## [2.0.2] - 2017-03-24

- Fix issue in return value for forget()

## [2.0.1] - 2017-03-09

- Fixed issue with override parameters

## [2.0.0] - 2017-03-07

- PHP 7+
- Now namespaced functions rather than class statics.
- Override now supports multiple array parameters to be overridden.
- Fixed problem with override method.

## [1.0.1] - 2017-03-24

- Add support for PHP 7
- Fix issue in return value for forget()

## [1.0.0] - 2015-02-22

- Initial commit
