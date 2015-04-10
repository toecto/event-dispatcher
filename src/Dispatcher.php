<?php

namespace Reactor\Events;

class Dispatcher {

    protected $wildcard = '*';
    protected $divider = '.';
    protected $listeners = array();

    public function setTokens($wildcard, $divider) {
        $this->wildcard = $wildcard;
        $this->divider = $divider;
    }

    public function addListener($event_name, $callable) {
        $this->listeners[$event_name][] = $callable;
        return $this;
    }

    public function addSubscriber(SubscriberInterface $subscriber) {
        foreach ($subscriber->getEventHandlers() as $event_name => $method_name) {
            $this->addListener($event_name, array($subscriber, $method_name));
        }
        return $this;
    }

    public function dispatch(Event $event) {
        $listeners = $this->getListeners($event->getName());
        foreach ($listeners as $callbacks) {
            call_user_func($callbacks, $event, $this);
        }
        return $this;
    }

    public function getListeners($event_name) {
        $listeners = array();
        foreach ($this->getSuperEventNames($event_name) as $s_event_name) {
            $listeners = array_merge($listeners, $this->getListenersStrict($s_event_name));
        }
        return $listeners;
    }

    protected function getListenersStrict($event_name) {
        if (isset($this->listeners[$event_name])) {
            return $this->listeners[$event_name];
        }
        return array();
    }

    protected function getSuperEventNames($event_name) {
        $super_names = array($event_name);
        $divider_pos = strrpos($event_name, $this->divider);
        while ($divider_pos !== false) {
            $event_name = substr($event_name, 0, $divider_pos);
            $super_names[] = $event_name . $this->divider . $this->wildcard;
            $divider_pos = strrpos($event_name, $this->divider);
        }
        $super_names[] = $this->wildcard;
        return $super_names;
    }

}
