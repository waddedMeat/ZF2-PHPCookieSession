<?php
 
namespace Loco\Session;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\ResponseSender\SendResponseEvent;

/**
 * Class SessionManager
 * @package Loco\Session\Zend
 */
class SessionManager extends \Zend\Session\SessionManager implements EventManagerAwareInterface {

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @param EventManagerInterface $em
     */
    public function setEventManager(EventManagerInterface $em) {
        $this->eventManager = $em;
        $em->getSharedManager()->attach(
            'Zend\Mvc\SendResponseListener',
            SendResponseEvent::EVENT_SEND_RESPONSE,
            array($this, 'writeClose')
        );
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager() {
        return $this->eventManager;
    }
}
 