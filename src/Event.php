<?php

namespace Reactor\Events;

class Event {
    
    protected $name;
    protected $message;
    protected $data;

    public function __construct($name, $message = null, $data = null) {
        $this->name = $name;
        $this->message = $message;
        $this->data = $data;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getName() {
        return $this->name;
    }
    
    public function getData() {
        return $this->data;
    }
    
}
