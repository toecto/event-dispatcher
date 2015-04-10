<?php 

namespace Reactor\Events;

class DispatcherStack extends Dispatcher {

	protected $stack = array();

	public function raise(Event $event) {
		$this->push($event);
	}

	private function processStack() {
		while ( $event = $this->pop() ) {
			parent::raise($event);
		}
		return $this;
	}

	private function pop() {
		return array_shift($this->stack);
	}

	public function push(Event $event) {
		$this->stack[] = $event;
	}

}

?>