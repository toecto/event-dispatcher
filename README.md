# event-dispatcher

Yet another event dispatcher.

Lightvert, support Event Name routing with wild cards, similar as RabbitMQ wild cards

## Installation with composer:

```javascript
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:toecto/event-dispatcher.git"
        }
    ],
    "require": {
        "reactor/event-dispatcher": "dev-master"
    }
  ```
## Use

```php

$dispatcher = new \Reactor\Events\Dispatcher();
$dispatcher->setTokens('*', '.'); // it is already done by default
$dispatcher->addListener('*.deleted', array($object, 'method'));
$dispatcher->dispatch(new \Reactor\Events\Event('user.deleted', 'name', array('some data')));

```
