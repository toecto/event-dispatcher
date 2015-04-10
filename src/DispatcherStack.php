<?php 

namespace Reactor\Events;

class DispatcherStack extends Dispatcher {

	protected $events = array();

	public function dispatch(Event $event) {
		$this->push($event);
		return $this;
	}

	private function process() {
		while ( $event = $this->pop() ) {
			parent::raise($event);
		}
	}

	private function loadEvent() {
		return array_shift($this->stack);
	}

	public function storeEvent(Event $event) {
		$this->stack[] = $event;
	}

}

?>
