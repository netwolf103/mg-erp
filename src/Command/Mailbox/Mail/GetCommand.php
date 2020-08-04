<?php

namespace App\Command\Mailbox\Mail;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Entity\Mail as MailEntity;
use App\Entity\Mail\Folder;

use App\Command\Mailbox\AbstractCommand;

/**
 * Get mails list.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class GetCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */   
    protected static $defaultName = 'app:mailbox:mail:get';

    /**
     * {@inheritdoc}
     */
    protected static $description =  'Get mails list.';

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Sync E-mails');

        $entityManager  = $this->getDoctrine()->getManager();

        $connectionName = $input->getArgument('connection');
        $connection     = $this->getImap()->get($connectionName);

        foreach ($this->getFolders() as $folder) {
            $io->section(sprintf('"%s" starting...', $folder->getFullpath()));

            $connection->switchMailbox($folder->getFullpath());

            $mailsIds = $connection->searchMailbox();
 
            foreach ($mailsIds as $mailId) {
                $io->section(sprintf('"%s" starting...', $mailId));

                $mail       = $connection->getMail($mailId);
                $mailBody   = $this->getBody($mailId, $connection->getImapStream());
                $date       = preg_replace('/\(.*\)$/', '', $mail->date);
                
                if (substr($date, -2) == 'UT') {
                    $date .= 'C';
                }

                $mailEntity = new MailEntity();
                $mailEntity
                    ->setFolder($folder)
                    ->setMsgId($mailId)
                    ->setDate(new \DateTimeImmutable($date))
                    ->setFromName($mail->fromName)
                    ->setFromAddress($mail->fromAddress)
                    ->setToAddress(join(', ', $mail->to))
                    ->setCcAddress(join(', ', $mail->cc))
                    ->setBccAddress(join(', ', $mail->bcc))
                    ->setReplyToAddress(join(', ', $mail->replyTo))
                    ->setSubject($mail->subject)
                    ->setBody($mailBody)
                    ->setCreatedAt(new \DateTimeImmutable())
                ;

                $entityManager->persist($mailEntity);
                $entityManager->flush();
            }
        }

        $io->success('E-mails successfully synced.');

        return 0;
    }

    /**
     * Return email body
     *
     * @param  int    $mailId
     * @param  resource $imapStream
     * @return string
     */
    protected function getBody(int $mailId, $imapStream): string
    {
        $body = $this->getPart($imapStream, $mailId, 'TEXT/HTML');

        if ($body == '') {
            $body = $this->getPart($imapStream, $mailId, 'TEXT/PLAIN');
        }

        return $body;
    }

    /**
     * Return email body part
     *
     * @param  resource       $imapStream
     * @param  int            $mailId
     * @param  string         $mimetype
     * @param  \stdClass|null $structure
     * @param  int|null       $partNumber
     * @return string
     */
    protected function getPart($imapStream, int $mailId, string $mimetype, ?\stdClass $structure = null, ?int $partNumber = null): string
    {
        if (is_null($structure)) {
            $structure = imap_fetchstructure($imapStream, $mailId, FT_UID);
        }

        if ($structure) {
            if ($mimetype == $this->getMimeType($structure)) {
                if (is_null($partNumber)) {
                    $partNumber = 1;
                }

                $text = imap_fetchbody($imapStream, $mailId, $partNumber, FT_UID);

                switch ($structure->encoding) {
                    case self::IMAP_ENCODE_BASE64:
                        return imap_base64($text);

                    case self::IMAP_ENCODE_QPRINT:
                        return imap_qprint($text);

                    default:
                        return $text;
               }
            }

            // multipart 
            if ($structure->type == 1) {
                foreach ($structure->parts as $index => $subStruct) {
                    $prefix = '';

                    if ($partNumber) {
                        $prefix = $partNumber . '.';
                    }

                    $data = $this->getPart($imapStream, $mailId, $mimetype, $subStruct, $prefix . ($index + 1));

                    if ($data) {
                        return $data;
                    }
                }
            }
        }

        return '';
    }

    /**
     * Return email body mine type.
     *
     * @param  \stdClass $structure
     * @return string
     */
    protected function getMimeType(\stdClass $structure): string
    {
        $primaryMimetype = [
            "TEXT",
            "MULTIPART",
            "MESSAGE",
            "APPLICATION",
            "AUDIO",
            "IMAGE",
            "VIDEO",
            "OTHER"
        ];

        if ($structure->subtype) {
            return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
        }

        return "TEXT/PLAIN";
    }

    /**
     * Convert string to UTF-8
     *
     * @param  string $str
     * @param  string $from_encoding
     * @return string
     */
    protected function str2UTF8(string $str, string $from_encoding): string
    {
        switch (strtoupper($from_encoding)) {
            case 'GBK':
            case 'GB2312':
                $str = mb_convert_encoding($str, 'UTF-8', 'GBK');
                break;
        }

        return $str;
    }

    /**
     * Return folders
     *
     * @return array
     */
    protected function getFolders(): array
    {
        $folders = $this->getDoctrine()
            ->getManager()
            ->getRepository(Folder::class)
            ->getAllEffective();

        return $folders;
    }
}
