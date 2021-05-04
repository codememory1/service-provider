<?php

namespace Codememory\Container\ServiceProvider\Exceptions;

use JetBrains\PhpStorm\Pure;

/**
 * Class ProviderNotFoundException
 * @package Codememory\Container\ServiceProvider\Exceptions
 *
 * @author  Codememory
 */
class ProviderNotFoundException extends ServiceProviderException
{

    /**
     * ProviderNotFoundException constructor.
     *
     * @param string $providerName
     */
    #[Pure] public function __construct(string $providerName)
    {

        parent::__construct(sprintf(
            'Provider %s was not found or the provider name was specified incorrectly',
            $providerName
        ));

    }

}