<?php

namespace App\Command\Mailbox\Box;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Command\Mailbox\AbstractCommand;

/**
 * List mailbox class.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ListCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected static $defaultName = 'app:mailbox:box:list';

    /**
     * {@inheritdoc}
     */
    protected static $description = 'List mailbox folders.';

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Mailbox Folders');

        $connectionName = $input->getArgument('connection');
        $connection     = $this->getImap()->get($connectionName);

        $rows =[];

        foreach($connection->getMailboxes('*') as $mailbox) {
            $name       = $mailbox['shortpath'];
            $fullpath   = $mailbox['fullpath'];

            $connection->switchMailbox($fullpath);
            $mailBox = $connection->statusMailbox();

            $messages   = $mailBox->messages ?? 'unknow';
            $unseen     = $mailBox->unseen ?? 'unknow';
            $uidnext    = $mailBox->uidnext ?? 'unknow';

            $rows[] = [$name, $fullpath, $messages, $unseen, $uidnext];
        }

        $table = new Table($output);
        $table
            ->setHeaders(['Name', 'Url', 'Messages', 'Unread', 'Next Msg Id'])
            ->setRows($rows)
        ;
        $table->render();        

        $io->success('All folders done.');

        return 0;
    }
}