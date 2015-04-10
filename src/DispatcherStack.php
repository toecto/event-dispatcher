<?php 

namespace Reactor\Events;

class DispatcherStack extends Dispatcher {

	protected $events = array();

	public function dispatch(Event $event) {
		$this->storeEvent($event);
		return $this;
	}

	public function process() {
		while ( $event = $this->loadEvent() ) {
			parent::dispatch($event);
		}
	}

	private function loadEvent() {
		return array_shift($this->events);
	}

	protected function storeEvent(Event $event) {
		$this->events[] = $event;
	}

}
