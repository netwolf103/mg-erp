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
 * Rename an existing mailbox.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class RenameCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected static $defaultName = 'app:mailbox:box:rename';

    /**
     * {@inheritdoc}
     */
    protected static $description = 'Rename an existing mailbox.';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->addArgument('oldName', InputArgument::REQUIRED, 'Current name of mailbox, which you want to rename (eg. \'MGErp\')')
            ->addArgument('newName', InputArgument::REQUIRED, 'New name of mailbox, to which you want to rename it (eg. \'MGErpTests\')')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $connectionName = $input->getArgument('connection');
        $oldName        = $input->getArgument('oldName');
        $newName        = $input->getArgument('newName');

        $this
            ->getImap()
            ->get($connectionName)
            ->renameMailbox($oldName, $newName)
        ;

        $this->renameMailbox($oldName, $newName);

        $io->success('Name modified successfully.');

        return 0;
    }

    /**
     * Rename an existing mailbox from $oldName to $newName.
     *
     * @param  string $oldName
     * @param  string $newName
     * @return self
     */
    protected function renameMailbox(string $oldName, string $newName): self
    {
        $entityManager  = $this->getDoctrine()->getManager();

        $folderEntity = $entityManager
            ->getRepository(Folder::class)
            ->findOneBy([
                'name' => $oldName
            ]);

        if ($folderEntity) {
            $folderEntity->setName($newName);
            $entityManager->persist($folderEntity);
            $entityManager->flush();
        }

        return $this;
    }
}