<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Twig extension class of mail
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class MailExtension extends AbstractExtension
{
    private $translator;

    /**
     * Init TranslatorInterface
     * 
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('mail_from', [$this, 'mailFrom']),
            new TwigFilter('mail_foler_name', [$this, 'mailFolderName']),
        ];
    }

    /**
     * Sender format
     *
     * @param  string $name
     * @param  string $address
     * @return string
     */
    public function mailFrom(string $name, string $address): string
    {
        return sprintf('%s <%s>', $name, $address);
    }

    /**
     * Return alias or name
     *
     * @param  string $name
     * @param  string $alias
     * @return string
     */
    public function mailFolderName(string $name, ?string $alias): string
    {
        return $alias ?: $name;
    }
}
