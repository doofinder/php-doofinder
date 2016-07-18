<?php

class NotAllowed extends Exception {}

class BadRequest extends Exception {}

class NotFound extends Exception {}

class QuotaExhausted extends Exception {}

class WrongResponse extends Exception {}

class ThrottledResponse extends Exception {}

class NotProcessedResponse extends Exception {}

function readError($response) {
  $error = json_decode($response, true);
  $error = $error['detail'];
  if (!isset($error)) {
    $error = $response;
  }
  return $error;
}

function handleErrors($statusCode, $response){
  switch($statusCode)
  {
    case 403:
      throw new NotAllowed("The user does not have permissions to perform this operation: ".readError($response));
    case 401:
      throw new NotAllowed("The user hasn't provided valid authorization: ".readError($response));
    case 404:
      throw new NotFound("Not Found: ".readError($response));
    case 409: // trying to post with an already used id
      throw new BadRequest("Request conflict: ".readError($response));
    case 429:
      if (stripos($response, 'throttled')) {
        throw new ThrottledResponse(readError($response));
      } else {
        throw new QuotaExhausted("The query quota has been reached. No more queries can be requested right now");
      }
  }

  if ($statusCode >= 500) {
    throw new WrongResponse("Server error: ".readError($response));
  }

  if ($statusCode >= 400) {
    throw new BadRequest("The client made a bad request: ".readError($response));
  }
}
