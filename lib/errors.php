<?php

class NotAllowed extends Exception{
}

class BadRequest extends Exception{
}

class QuotaExhausted extends Exception{
}

class WrongResponse extends Exception{
}


function handleErrors($statusCode, $response){
    switch($statusCode)
    {
    case 403:
        throw new NotAllowed("The user does not have permissions to perform this operation: $response");
    case 401:
        throw new NotAllowed("The user hasn't provided valid authorization: $response");
    case 404:
        throw new BadRequest("Not Found: $response");
    case 409: // trying to post with an already used id
        throw new BadRequest("Request conflict: $response");
    case 429:
        throw new QuotaExhausted("The query quota has been reached. No more queries can be requested right now");
    }
    if($statusCode >= 500){
        throw new WrongResponse("Server error: $response");
    }
    if($statusCode >= 400){
        throw new BadRequest("The client made a bad request: $response");
    }
}