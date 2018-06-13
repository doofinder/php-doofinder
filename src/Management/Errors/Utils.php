<?php

namespace Doofinder\Api\Management\Errors;

use Doofinder\Api\Management\Errors\BadRequest;
use Doofinder\Api\Management\Errors\NotAllowed;
use Doofinder\Api\Management\Errors\NotFound;
use Doofinder\Api\Management\Errors\QuotaExhausted;
use Doofinder\Api\Management\Errors\ThrottledResponse;
use Doofinder\Api\Management\Errors\TypeAlreadyExists;
use Doofinder\Api\Management\Errors\WrongResponse;


class Utils {
  public static function handleErrors($statusCode, $response) {
    switch ($statusCode) {
      case 403:
        return new NotAllowed("The user does not have permissions to perform this operation: ".Utils::readError($response));
      case 401:
        return new NotAllowed("The user hasn't provided valid authorization: ".Utils::readError($response));
      case 404:
        return new NotFound("Not Found: ".Utils::readError($response));
      case 409:
        if (preg_match('/indexing.*progress/i', $response) == 1) {
          // The search engine is locked
          return new IndexingInProgress(Utils::readError($response));
        } else if (preg_match('/type.*already created/i', $response) == 1) {
          return new TypeAlreadyExists(Utils::readError($response));
        } else {
          return new BadRequest("Request conflict: ".Utils::readError($response)); // trying to post with an already used id
        }
      case 429:
        if (stripos($response, 'throttled')) {
          return new ThrottledResponse(Utils::readError($response));
        } else {
          return new QuotaExhausted("The query quota has been reached. No more queries can be requested right now");
        }
    }

    if ($statusCode >= 500) {
      return new WrongResponse("Server error: ".Utils::readError($response));
    }

    if ($statusCode >= 400) {
      return new BadRequest("The client made a bad request: ".Utils::readError($response));
    }

    return false;
  }

  private static function readError($response) {
    $error = json_decode($response, true);
    if (is_null($error) || !isset($error['detail'])) {
      $error = $response;
    } else {
      $error = $error['detail'];
    }

    return $error;
  }
}
