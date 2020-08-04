<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use SecIT\ImapBundle\Service\Imap;
use App\Api\Magento1x\Soap\Newsletter\SubscriberSoap;

class TestCommand extends Command
{
    protected static $defaultName = 'app:test';

    protected $imap;

    public function __construct(Imap $imap)
    {
        $this->imap = $imap;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        try {
            $test = new SubscriberSoap('http://www.gissio-dev.com', 'erp', 'netwolf103');
            print_r($test->callList());
            exit;

            $exampleConnection = $this->imap->get('mail_connection');


            $exampleConnection->switchMailbox('{imap.exmail.qq.com:143}Sent Messages');
            $mailBox = $exampleConnection->statusMailbox();
            print_r($mailBox);
            exit;
            $folders = $exampleConnection->getMailboxes('*');

            foreach($folders as $folder) {
                print_r($folder);
            } 

            $exampleConnection->switchMailbox('{imap.exmail.qq.com:143}Sent Messages');
            $mailsIds = $exampleConnection->searchMailbox('ALL');

            //print_r($exampleConnection->getMailboxInfo());
                $mail = $exampleConnection->getMail(12);

                //print_r($mail);exit;
                //$messageId = $mail->messageId;
                //print_r($exampleConnection->getMailMboxFormat(1));
                //print_r(imap_fetchbody($exampleConnection->getImapStream(), 4892, '', FT_UID));
               // print_r(imap_fetchstructure($exampleConnection->getImapStream(), 4892));
                print_r($this->getBody(12, $exampleConnection->getImapStream()));

                //print_r(imap_qprint($exampleConnection->getMailMboxFormat(4892)));
                exit;
                $body = $exampleConnection->getMailMboxFormat(1);
                preg_match('/boundary="(.*)"/', $body, $matches);
                //print_r($matches);
                $boundary = $matches[1] ?? '';
                
                $body = explode($boundary, $body);
                $body = $body[3] ?? '';
                $body = trim($body, " \t\n\r\0\x0B-");

                preg_match('/Content-Transfer-Encoding: (.*)/', $body, $matches);
                $contentEncoding = $matches[1] ?? 'base64';
                $contentEncoding = trim($contentEncoding);

                $rawBody = explode($matches[0], $body);

                preg_match('/Content-Type: (.*);/', $body, $matches);
                $contentType = $matches[1] ?? '';
                $contentType = trim($contentType);

                preg_match('/charset="(.*)"/', $body, $matches);
                $charset = $matches[1] ?? '';
                $charset = trim($charset);

                $body = $rawBody[1] ?? '';
                $body = trim($body);

                switch ($contentEncoding) {
                    case 'quoted-printable':
                        echo mb_convert_encoding(imap_qprint($body), 'UTF-8', 'GBK');
                        break;

                    case 'base64':
                        echo base64_decode($body);
                        break;
                    
                    default:
                        # code...
                        break;
                }

                if($mail->hasAttachments()) {
                    print_r($mail->getAttachments());
                }

            foreach ($mailsIds as $mailsId) {

            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

//$isConnectable = $this->imap->testConnection('example_connection');
//var_dump($isConnectable);        

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }

    protected function getBody($uid, $imap) {
        $body = $this->get_part($imap, $uid, "TEXT/HTML");
        // if HTML body is empty, try getting text body
        if ($body == "") {
            $body = $this->get_part($imap, $uid, "TEXT/PLAIN");
        }
        return $body;
    }

    protected function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false) {
        if (!$structure) {
               $structure = imap_fetchstructure($imap, $uid, FT_UID);
        }
        if ($structure) {
            if ($mimetype == $this->get_mime_type($structure)) {
                if (!$partNumber) {
                    $partNumber = 1;
                }
                $text = imap_fetchbody($imap, $uid, $partNumber, FT_UID);
                switch ($structure->encoding) {
                    case 3: return imap_base64($text);
                    case 4: return imap_qprint($text);
                    default: return $text;
               }
           }

            // multipart 
            if ($structure->type == 1) {
                foreach ($structure->parts as $index => $subStruct) {
                    $prefix = "";
                    if ($partNumber) {
                        $prefix = $partNumber . ".";
                    }
                    $data = $this->get_part($imap, $uid, $mimetype, $subStruct, $prefix . ($index + 1));
                    if ($data) {
                        return $data;
                    }
                }
            }
        }
        return false;
    }

    protected function get_mime_type(\stdClass $structure) {
        $primaryMimetype = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");

        if ($structure->subtype) {
           return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
        }
        return "TEXT/PLAIN";
    }    
}
