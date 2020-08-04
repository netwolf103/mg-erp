<?php

namespace App\Command\Mailbox\Box;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

use App\Command\Mailbox\AbstractCommand;

use App\Entity\Mail\Folder;

/**
 * Creates a new mailbox.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CreateCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected static $defaultName = 'app:mailbox:box:create';

    /**
     * {@inheritdoc}
     */
    protected static $description =  'Creates a new mailbox.';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Name of new mailbox (eg. \'MGErp\')')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $connectionName = $input->getArgument('connection');
        $name           = $input->getArgument('name');

        $connection     = $this->getImap()->get($connectionName);

        $connection->createMailbox($name);

        foreach($connection->getMailboxes($name) as $mailbox) {
            $io->section(sprintf('"%s" => %s', $mailbox['shortpath'], $mailbox['fullpath']));

            $this->createMailbox($mailbox);
        }

        $io->success('Created successfully.');

        return 0;
    }

    /**
     * Creates a new mailbox.
     * 
     * @param  array  $mailbox
     * @return self
     */
    protected function createMailbox(array $mailbox): self
    {
        $entityManager  = $this->getDoctrine()->getManager();
        $folderEntity   = new Folder();

        $folderEntity
            ->setName($mailbox['shortpath'])
            ->setFullpath($mailbox['fullpath'])
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
        ;

        $entityManager->persist($folderEntity);
        $entityManager->flush();

        return $this;
    }
}