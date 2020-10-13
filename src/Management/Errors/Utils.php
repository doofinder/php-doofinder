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


class Utils {
  public static function handleErrors($statusCode, $response) {
    switch ($statusCode) {
      case 400:
        if (Utils::getCode($response) == "bad_params" || Utils::getCode($response) == "index_internal_error") {
            return new BadRequest("Request contains wrong parameter or values.");
        } elseif (Utils::getCode($response) == "invalid_boost_value") {
            return new BadRequest("Invalid value for item boost field.");
        } elseif (Utils::getCode($response) == "invalid_field_name") {
            return new BadRequest("Items field names contains invalid characters.");
        }else {
            return new BadRequest("The client made a bad request.");
        };
      case 401:
        return new NotAllowed("The user hasn't provided valid authorization.");
      case 403:
        return new NotAllowed("The user does not have permissions to perform this operation.");
      case 404:
        return new NotFound("Not Found.");
      case 408:
        return new APITimeout("Operation has surpassed time limit.");
      case 409:
        if (Utils::getCode($response) == "searchengine_locked") {
            return new ConflictRequest("The request search engine is locked by another operation."); 
        } elseif (Utils::getCode($response) == "too_many_temporary") {
            return new ConflictRequest("There are too many temporary index."); 
        }else {
            return new ConflictRequest("Request conflict.");
        };
      case 413:
          return new TooManyItems("Requests contains too many items.");
      case 429:
        return new TooManyRequests("Too many requests by second.");
      case 500:
        return new WrongResponse("Server error.");
      case 502:
        return new BadGateway("Bad Gateway Error connecting to Doofinder.");
    }

    return false;
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