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
 * Get mailbox class.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class GetCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected static $defaultName = 'app:mailbox:box:get';

    /**
     * {@inheritdoc}
     */
    protected static $description = 'Get mailbox folders list.';

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Get mailbox folders');

        $entityManager  = $this->getDoctrine()->getManager();

        $connectionName = $input->getArgument('connection');
        $connection     = $this->getImap()->get($connectionName);
        $folders        = $connection->getMailboxes('*');

        foreach($folders as $folder) {
            $io->section(sprintf('"%s" starting...', $folder['shortpath']));

            $folderEntity = $this->_getFolderEntity($folder['fullpath']);

            $folderEntity
                ->setName($folder['shortpath'])
                ->setFullpath($folder['fullpath'])
                ->setUpdatedAt(new \DateTimeImmutable())
            ;

            $entityManager->persist($folderEntity);
        }

        $entityManager->flush();

        $io->success('Folder successfully synced.');

        return 0;
    }

    /**
     * Return Folder
     *
     * @param  string $fullpath
     * @return Folder
     */
    private function _getFolderEntity(string $fullpath): Folder
    {
        $folderEntity = $this->getDoctrine()
            ->getManager()
            ->getRepository(Folder::class)
            ->findOneBy([
                'fullpath' => $fullpath
            ]);

        if (!$folderEntity) {
            $folderEntity = new Folder();
            $folderEntity->setCreatedAt(new \DateTimeImmutable());
        }

        return $folderEntity;
    }
}
