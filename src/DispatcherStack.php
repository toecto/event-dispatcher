<?php 

namespace Reactor\Events;

class DispatcherStack extends Dispatcher {

	protected $stack = array();

	public function raise(Event $event) {
		$this->pushStack($event);
		return $this->processStack();
	}

	private function processStack() {
		while ( $event = $this->popStack() ) {
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