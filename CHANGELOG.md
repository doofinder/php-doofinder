# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [7.0.7] - 2025-01-21
### Fixed
- Fixed RequestException constructor to properly handle integer error codes
- Added CURL error code mapping to appropriate HTTP status codes (408 for timeouts, 503 for connection errors, 500 for other errors)
- Set HTTP client timeout to 30 seconds to match server configuration

## [7.0.6] - 2025-07-29
### Fixed
- Pass the curl error message as the first argument when constructing `RequestException`

## [7.0.5] - 2025-01-20
### Fixed (@felipegear4music)
- The code passed to the Exception parent should be an integer
  - Exception::__construct(): Argument #2 ($code) must be of type int

## [7.0.4] - 2023-03-13
### Changed
- Deprecated "site_url" field in Search Engine object

## [7.0.3] - 2022-11-21
### Changed
- Updated the "Installation & Usage" section of the readmes

## [7.0.2] - 2022-11-08
### Changed
- Documentation update

## [7.0.1] - 2022-10-11
### Changed
- Fixed error in readme files

## [7.0.0] - 2022-10-06
### Changed
- Tests changed.
    - Travis CI tests enhancements deleted
    - Github CI tests implemented
- Client for Search API v6.

## [6.1.2] - 2020-10-20
### Changed
- Added response body to error classes.
## [6.1.1] - 2020-10-20
### Fixed
- Some bugs with error classes in Management API (thanks to @arkadiuszzietek).

## [6.1.0] - 2020-10-16
### Added
- Custom error classes for Management API.

## [6.0.0] - 2020-06-30
### Added
- New client for Management API v2.

### Changed
- Refactored client for Search API v5.
  - Holds less state.
  - Refactor some methods related to params processing.

### Removed
- Client for Management API v1.
- Some methods related to facets and sorting in search client.

## [5.9.1] - 2019-11-15
### Fixed
- Guarantee a max of 2 requests/s when getting search engines. Thanks to @magently.

## [5.9.0] - 2019-09-23
### Added
- Now all exceptions inherit from a custom base exception (`DoofinderException`). Thanks to @julien-jean.

### Changed
- Travis CI tests enhancements. Thanks to @julien-jean.

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
