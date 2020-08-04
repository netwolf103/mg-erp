<?php

namespace App\Command\Mailbox\Box;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

use App\Command\Mailbox\AbstractCommand;

use App\Entity\Mail\Folder;

/**
 * Get information about the mailbox.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class StatCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected static $defaultName = 'app:mailbox:box:stat';

    /**
     * {@inheritdoc}
     */
    protected static $description =  'Get information about the mailbox.';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Name of mailbox (eg. \'MGErp\')')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $connectionName = $input->getArgument('connection');
        $name        = $input->getArgument('name');

        if (!$mailbox = $this->getMailbox($name)) {
            throw new \Exception(sprintf('"%s" not exists.', $name));
        }

        $io->title(sprintf('"%s" stat', $name));

        $connection = $this->getImap()->get($connectionName);

        $connection->switchMailbox($mailbox->getFullpath());

        $mailBox = $connection->statusMailbox();

        $table = new Table($output);
        $table
            ->setHeaders(['Messages', 'Unread', 'Next Msg Id'])
            ->setRows([
                [$mailBox->messages, $mailBox->unseen, $mailBox->uidnext],
            ])
        ;
        $table->render();

        return 0;
    }

    /**
     * Get a mailbox.
     *
     * @param  string $name
     * @return self
     */
    protected function getMailbox(string $name): ?Folder
    {
        $folderEntity = $this->getDoctrine()->getManager()
            ->getRepository(Folder::class)
            ->findOneBy([
                'name' => $name
            ]);

        return $folderEntity;
    }    
}