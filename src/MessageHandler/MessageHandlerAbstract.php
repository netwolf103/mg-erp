<?php
namespace App\MessageHandler;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

use App\Traits\DatetimeTrait;

/**
 * Abstract message handler.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
abstract class MessageHandlerAbstract implements MessageHandlerInterface
{
    use DatetimeTrait;
    
    private $doctrine;
    private $parameter;
    private $bus;

    public function __construct(ManagerRegistry $doctrine, ParameterBagInterface $parameter, MessageBusInterface $bus)
    {
        $this->doctrine = $doctrine;
        $this->parameter = $parameter;
        $this->bus = $bus;

        $this->initialize();
    }

    /**
     * Dispatches a message to the bus.
     *
     * @param object|Envelope $message The message or the message pre-wrapped in an envelope
     */
    public function dispatchMessage($message)
    {
        $this->bus->dispatch($message);
    }

    /**
     * Get ManagerRegistry instance.
     * 
     * @return ManagerRegistry
     */
    public function getDoctrine(): ManagerRegistry
    {
        return $this->doctrine;
    }

    /**
     * Get ParameterBagInterface instance.
     * 
     * @return ParameterBagInterface
     */
    public function getParameter(): ParameterBagInterface
    {
        return $this->parameter;
    }

    /**
     * Print message.
     * 
     * @param  string $message
     * @return self
     */
    protected function success(string $message): self
    {
        echo sprintf("Success: %s\r\n", $message);

        return $this;
    }

    /**
     * Print message.
     * 
     * @param  string $message
     * @return self
     */
    protected function error(string $message): self
    {
        echo sprintf("Failure: %s\r\n", $message);

        return $this;        
    }

    /**
     * Initialization
     * 
     * @return self
     */
    protected function initialize()
    {
        return $this; 
    }    	
}