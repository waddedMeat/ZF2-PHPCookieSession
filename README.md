
# Cookie Session Handler #

To use the session handler in ZF2, you will need to ensure the session is written before any output is returned.  This can be achieved by adding a listenter to the `SendResponseEvent::EVENT_SEND_RESPONSE` event, or use the provided SessionManager.  This can be done in your `application.config.php`

```
'service_manager' => array(
	'invokables' => array(
	    'SessionManager' => 'Loco\Session\SessionManager',
	),
)
````

To encrypt the client side session, you will need to provide your own encryption method that implements `\Loco\Crypt\CipherInterface`.  For more information regarding how to use the provided encryption method, please see the [ZF2 Documentation on Block Cipher](http://framework.zend.com/manual/2.0/en/modules/zend.crypt.block-cipher.html)

```
$cipher = new \Loco\Crypt\BlockCipher(new \Zend\Crypt\Symmetric\Mcrypt(array('algo' => 'aes')));
$cipher->setKey('This is my application secret key');
```

This will need to be high enough in your stack that it is called before anything else attempts to start the session.  The location of this code could very, but placing it at the top of your `onBootstrap` method sholud suffice.

```
// assuming $sm is your ServiceManager and SessionManager is a Loco\Session\SessionManager
$sessionManager = $sm->get('SessionManager');

// ClientSessionAdapter is a ZF2 wrapper to the ClientSession
$sessionAdapter = new \Loco\Session\SaveHandler\ClientSessionAdapter();

// set the cipher
$sessionAdapter->setCipher($cipher);

$sessionManager->setSaveHandler($sessionAdapter);

// the SessionManager NEEDS the EventManager to set up the necessary listeners
$sessionManager->setEventManager($eventManager);

$sessionManager->start();
```