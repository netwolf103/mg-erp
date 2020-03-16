<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use App\Controller\QueryFilterTrait;
use App\Event\ControllerEvent;

/**
 * Controller of AdminControllerAbstract.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
abstract class AdminControllerAbstract extends AbstractController
{
	use QueryFilterTrait;

    /**
     * Controller default route name.
     * 
     * @var string
     */
    protected static $_defaultRoute = 'admin_dashboard';

	protected $eventDispatcher;

	public function __construct(EventDispatcherInterface $dispatcher)
	{
		$this->eventDispatcher = $dispatcher;
	}

    /**
     * Renders a action.
     *
     * @return string
     */
    public function _renderAction(): string
    {
        return '';
    }
    
    /**
     * Get EventDispatcherInterface object.
     * 
     * @return EventDispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->eventDispatcher;
    }

	/**
	 * Verify object by object id;
	 * 
	 * @param  array  $object
	 * @return ControllerEvent
	 */
	protected function verifyData(array $objects): ControllerEvent
	{
        $event = $this->eventDispatcher->dispatch(
            new ControllerEvent($objects),
            ControllerEvent::DATA_VERIFY
        );

        return $event;
	}

    /**
     * {@inheritdoc}
     */ 
    protected function addSuccessFlash(string $message)
    {
        $this->addFlash(
            'success',
            $message
        );    	
    }

    /**
     * {@inheritdoc}
     */ 
    protected function addErrorFlash(string $message)
    {
        $this->addFlash(
            'danger',
            $message
        );    	
    }

    /**
     * Redirect form url or route name.
     * 
     * @param  string      $url
     * @param  array       $parameters
     * @param  int|integer $status
     * @return RedirectResponse
     */
    protected function back(?string $default = null, array $parameters = [], int $status = 302): RedirectResponse
    {
        if ($request = $this->getCurrentRequest()) {

            $url = $request->get('_referer');

            if (is_null($url)) {
                $url = $request->headers->get('referer', $default ?: static::$_defaultRoute);
            }
        } else {
            $url = $default ?: static::$_defaultRoute;
        }

        if (preg_match('/^http(s?):\/\//', $url)) {
            return $this->redirect($url, $status);
        } else {
            return $this->redirectToRoute($url, $parameters, $status);
        }
    }

    /**
     * Return current request.
     * 
     * @return Request|null
     */
    protected function getCurrentRequest(): ?Request
    {
        return $this->container->get('request_stack')->getCurrentRequest();
    }

    /**
     * Renders a view.
     *
     * @final
     */
    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $parameters['_controller'] = $this;

        return parent::render($view, $parameters, $response);
    }
}