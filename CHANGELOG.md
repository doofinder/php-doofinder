#Version 5.

##v5.6.1
  * Breaking changes
    - getFacet method return array simplified
    - former 'getFacet' method is now called 'getLegacyFacet'

##v5.5.1
  * New features
    - added deleteItems method

##v5.5.0
  * New features
    - Added phpunit tests
  * Other stuff
    - some small refactoring

##v5.4.3
  * New Features
    - autoload for those not using composes
  * Other stuff
    - bugfixes
    - decent formatting

##v5.3.1
  * Breaking changes
    - complete rewrite of the file structure: psr4 compliant
  * New Features
    - added to the composer repository

##v5.2.6
  * New Features
    - added sort parameter.

##v5.2.5
  * New features
    - stats retrieval

##v5.2.4
  * Other stuff
    - bugfixes

##v5.2.3
  * Other stuff
    - bugfixes

##v5.2.2
  * mainteinance release

##v5.2.1
  * new features
    - Search Client: getOptions method
  * Breaking changes
    - Search Client: https mandatory

##v5.*
  * New features
    - Allow unprefixed params
    - Allow custom query parameter name
  * Breaking changes
    - API Key Authorization. API Key required in client constructor

#Version 4.

 - CamelCase convention for everything. "has_next(0" is now "hasNext()"
 - And empty query() prompts a "match all" query
 - Facets/Filtering support
