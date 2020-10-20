<?php

namespace Doofinder\Management\Errors;

use Doofinder\Management\Errors\APITimeout;
use Doofinder\Management\Errors\BadGateway;
use Doofinder\Management\Errors\BadRequest;
use Doofinder\Management\Errors\NotAllowed;
use Doofinder\Management\Errors\NotFound;
use Doofinder\Management\Errors\ConflictRequest;
use Doofinder\Management\Errors\TooManyItems;
use Doofinder\Management\Errors\TooManyRequests;
use Doofinder\Management\Errors\WrongResponse;
use DoofinderManagement\ApiException;


class Utils {
  public static function handleErrors($statusCode, $response, $exception) {
    switch ($statusCode) {
      case 400:
        if (Utils::getCode($response) == "bad_params" || Utils::getCode($response) == "index_internal_error") {
            return new BadRequest("Request contains wrong parameter or values.", null, $exception);
        } elseif (Utils::getCode($response) == "invalid_boost_value") {
            return new BadRequest("Invalid value for item boost field.", null, $exception);
        } elseif (Utils::getCode($response) == "invalid_field_name") {
            return new BadRequest("Items field names contains invalid characters.", null, $exception);
        }else {
            return new BadRequest("The client made a bad request.", null, $exception);
        };
      case 401:
        return new NotAllowed("The user hasn't provided valid authorization.", null, $exception);
      case 403:
        return new NotAllowed("The user does not have permissions to perform this operation.", null, $exception);
      case 404:
        return new NotFound("Not Found.", null, $exception);
      case 408:
        return new APITimeout("Operation has surpassed time limit.", null, $exception);
      case 409:
        if (Utils::getCode($response) == "searchengine_locked") {
            return new ConflictRequest("The request search engine is locked by another operation.", null, $exception);
        } elseif (Utils::getCode($response) == "too_many_temporary") {
            return new ConflictRequest("There are too many temporary index.", null, $exception);
        }else {
            return new ConflictRequest("Request conflict.", null, $exception);
        };
      case 413:
          return new TooManyItems("Requests contains too many items.", null, $exception);
      case 429:
        return new TooManyRequests("Too many requests by second.", null, $exception);
      case 500:
        return new WrongResponse("Server error.", null, $exception);
      case 502:
        return new BadGateway("Bad Gateway Error connecting to Doofinder.", null, $exception);
    }

    return new ApiException("Unknown error", null, $exception);
  }

  private static function getCode($response) {
    $error = json_decode($response, true)["error"];

    if (is_null($error) || !isset($error["code"])) {
      $error = $response;
    } else {
      $error = $error["code"];
    }

    return $error;
  }
}