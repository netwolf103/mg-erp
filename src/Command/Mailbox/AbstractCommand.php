<?php

namespace App\Command\Mailbox;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\DependencyInjection\ContainerInterface;
use SecIT\ImapBundle\Service\Imap;

/**
 * Mailbox command abstract class.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
abstract class AbstractCommand extends \App\Command\AbstractCommand
{
    /**
     * Imap encode
     *
     * 3: base64
     * 4: qprint
     */
    const IMAP_ENCODE_BASE64 = 3;
    const IMAP_ENCODE_QPRINT = 4;

    /**
     * Imap object
     *
     * @var SecIT\ImapBundle\Service\Imap
     */
    protected $imap;    

    /**
     * Init imap
     *
     * @param Imap $imap
     */
    public function __construct(ContainerInterface $container, Imap $imap)
    {
        $this->setContainer($container);
        $this->imap = $imap;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription(static::$description)
            ->addArgument('connection', InputArgument::REQUIRED, 'Connection name')
        ;
    }

    /**
     * Return Imap object
     *
     * @return Imap
     */
    protected function getImap(): Imap
    {
        return $this->imap;
    }    
}