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
 * Deletes a specific mailbox.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class DeleteCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected static $defaultName = 'app:mailbox:box:delete';

    /**
     * {@inheritdoc}
     */
    protected static $description = 'Deletes a specific mailbox.';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Name of mailbox, which you want to delete (eg. \'MGErp\')')
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

        $this
            ->getImap()
            ->get($connectionName)
            ->deleteMailbox($name)
        ;

        $this->deleteMailbox($name);

        $io->success('Deleted successfully.');

        return 0;
    }

    /**
     * Deletes a specific mailbox.
     * 
     * @param  array  $mailbox
     * @return self
     */
    protected function deleteMailbox(string $name): self
    {
        $entityManager  = $this->getDoctrine()->getManager();

        $folderEntity = $entityManager
            ->getRepository(Folder::class)
            ->findOneBy([
                'name' => $name
            ]);

        if ($folderEntity) {
            $entityManager->remove($folderEntity);
            $entityManager->flush();
        }

        return $this;
    }
}