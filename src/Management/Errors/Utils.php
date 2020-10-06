<?php

namespace Doofinder\Management\Errors;

use Doofinder\Management\Errors\APITimeout;
use Doofinder\Management\Errors\BadGateway;
use Doofinder\Management\Errors\BadRequest;
use Doofinder\Management\Errors\NotAllowed;
use Doofinder\Management\Errors\NotFound;
use Doofinder\Management\Errors\SearchEngineLocked;
use Doofinder\Management\Errors\TooManyItems;
use Doofinder\Management\Errors\TooManyRequests;
use Doofinder\Management\Errors\TooManyTemporary;
use Doofinder\Management\Errors\WrongResponse;


class Utils {
  public static function handleErrors($statusCode, $response) {
    switch ($statusCode) {
      case 400:
        if (Utils::readError($response) == "bad_params") {
            return new BadRequest("Request contains wrong parameter or values: ".Utils::readError($response));
        } elseif (Utils::readError($response) == "index_internal_error") {
            return new BadRequest("Error in the internal index engine: ".Utils::readError($response));
        } elseif (Utils::readError($response) == "invalid_boost_value") {
            return new BadRequest("Invalid value for item boost field: ".Utils::readError($response));
        } elseif (Utils::readError($response) == "invalid_field_name") {
            return new BadRequest("Items field names contains invalid characters: ".Utils::readError($response));
        }else {
            return new BadRequest("The client made a bad request: ".Utils::readError($response));
        };
      case 401:
        return new NotAllowed("The user hasn't provided valid authorization: ".Utils::readError($response));
      case 403:
        return new NotAllowed("The user does not have permissions to perform this operation: ".Utils::readError($response));
      case 404:
        return new NotFound("Not Found: ".Utils::readError($response));
      case 408:
        return new APITimeout("Operation has surpassed time limit: ".Utils::readError($response));
      case 409:
        if (Utils::readError($response) == "searchengine_locked") {
            return new SearchEngineLocked("The request search engine is locked by another operation: ".Utils::readError($response)); 
        } elseif (Utils::readError($response) == "too_many_temporary") {
            return new TooManyTemporary("There are too many temporary index: ".Utils::readError($response)); 
        }else {
            return new BadRequest("Request conflict: ".Utils::readError($response));
        };
      case 413:
          return new TooManyItems("Requests contains too many items: ".Utils::readError($response));
      case 429:
        return new TooManyRequests("Too many requests by second: ".Utils::readError($response));
      case 500:
        return new WrongResponse("Server error: ".Utils::readError($response));
      case 502:
        return new BadGateway("Bad Gateway Error connecting to Doofinder.: ".Utils::readError($response));
    }

    return false;
  }

  private static function readError($response) {
    $error = json_decode($response, true)["error"];

    if (is_null($error) || !isset($error["code"])) {
      $error = $response;
    } else {
      $error = $error["code"];
    }

    return $error;
  }
}