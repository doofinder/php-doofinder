# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [5.8.0] - 2018-10-23
### Added
  - `getItems()` method in order to deprecate the old `items()` method.
  - Deprecation message for `getDatatypes()` method in favor of the `getTypes()` method.
  - `getAllTypes()` method to retrieve all user and internal datatypes for a search engine.
  - `getInternalTypes()` method to retrieve internal datatypes only for a search engine.

### Changed
  - `getTypes()` now only returns user-defined datatypes and not internal ones so passing the result of that function to `deleteType()` doesn't produce unexpected results (like removing all search suggestions).

## [5.7.6] - 2018-09-12
### Added
  - SearchEngines CRUD
  - Support for multiple types deletion in a single request.

### Changed
  - Small refactoring to allow changing the endpoint easily for dev.

## [5.7.5] - 2018-09-03
### Added
  - Support for partial updates.

## [5.7.4] - 2018-06-13
### Added
  - TypeAlreadyExists error.

## [5.7.3] - 2018-02-20
### Added
  - Stats endpoint.

## [5.7.2]
### Fixed
  - Version in `composer.json`.

## [5.7.1]
### Fixed
  - Restored redirection in the response.

## [5.7.0]
### Added
  - Throw exception when api key is not correctly set.

## [5.6.3]
### Fixed
  - Force `http_buid_query` to use `&` separator.

## [5.6.2]
### Fixed
  - Bug reading error response details.

## [5.6.1]
### Added
  - Support for exclusion filters (thanks to @sPooKee).

## [5.6.0]
### Changed
  - `getFacet` method returns simplified array. **BREAKING**
  - Former `getFacet` method is now called `getLegacyFacet`. **BREAKING**

## [5.5.1]
### Added
  - Added `deleteItems` method.

## [5.5.0]
### Added
  - Added phpUnit tests.

### Changed
  - Some small refactoring

## [5.4.3]
### Added
  - Autoload for those not using Composer.

### Changed
  - Some bugfixes.
  - Decent formatting.

## [5.3.1]
### Added
  - Added to the Composer repository.

### Changed
  - Complete rewrite of the file structure: PSR-4 compliant.

## [5.2.6]
### Added
  - Added sort parameter.

## [5.2.5]
### Added
  - Stats retrieval.

## [5.2.4]
### Changed
  - Bugfixes.

## [5.2.3]
### Changed
  - Bugfixes.

## [5.2.2]
### Changed
  - Mainteinance release.

## [5.2.1]
### Added
  - Search Client: `getOptions` method.

### Changed
  - Search Client: HTTPS is mandatory now. **BREAKING**

## [5.1]
### Added
  - Allow unprefixed parameters.
  - Allow custom query parameter name.
  - API Key Authorization. API Key required in client constructor. **BREAKING**
