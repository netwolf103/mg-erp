<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

use App\Entity\User;

/**
 * Twig extension for user
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class UserExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */     
    public function getFilters(): array
    {
        return [
            new TwigFilter('user_status', [$this, 'getStatus']),
        ];
    }

    /**
     * Get user status.
     * 
     * @param  int $value
     * @return string
     */
    public function getStatus(int $value): string
    {
        $status = array_flip(User::getStatusList());

        return $status[$value] ?? $value;
    }
}
