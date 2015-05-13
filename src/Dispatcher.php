<?php

namespace Reactor\Events;

class Dispatcher {

    protected $wildcard = '#';
    protected $wordcard = '*';
    protected $divider = '.';
    protected $listeners = array();
    protected $cache = array();


    public function setTokens($wildcard, $wordcard, $divider = '.') {
        $this->wildcard = $wildcard;
        $this->divider = $divider;
    }

    public function addListener($event_name, $callable) {
        $this->listeners[$this->getPregMask($event_name)][] = $callable;
        $this->cache = array();
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
        if (isset($this->cache[$event_name])) {
            return $this->cache[$event_name];
        }
        $matched_listeners = array();
        foreach ($this->listeners as $mask => $listeners) {
            if (preg_match($mask, $event_name)) {
                $matched_listeners = array_merge($matched_listeners, $listeners);
            }
        }
        $this->cache[$event_name] = $matched_listeners;
        return $matched_listeners;
    }

    protected function getPregMask($event_mask) {
        $all = '.+';
        $element = '[^'.preg_quote($this->divider).']+';
        return '/^'.str_replace(array($all, $element), array($all, $element), $event_mask).'$/';
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
