<?php
namespace Doofinder\Management\Errors;

use DoofinderManagement\ApiException;

class DoofinderError extends \Exception {
    /**
     * The HTTP body of the server response either as Json or string.
     *
     * @var mixed
     */
    protected $body;

    public function __construct($message, $code = 0, ApiException $previous=NULL, $body) {
        parent::__construct($message, $code, $previous);
        $this->body = $body;
    }

    // custom string representation of object
    public function __toString() {
        return ": [{$this->message}]: {$this->body}\n";
    }
    
    /**
     * Gets the HTTP body of the server response either as Json or string
     *
     * @return mixed HTTP body of the server response either as \stdClass or string
     */
    public function getBody() {
        return $this->body;
    }
}