<?php

namespace Codememory\Container\ServiceProvider\Exceptions;

use JetBrains\PhpStorm\Pure;

/**
 * Class ProviderNamespaceNotSpecifiedException
 * @package Codememory\Container\ServiceProvider\Exceptions
 *
 * @author  Codememory
 */
class ProviderNamespaceNotSpecifiedException extends ServiceProviderException
{

    /**
     * ProviderNamespaceNotSpecifiedException constructor.
     *
     * @param string $name
     */
    #[Pure] public function __construct(string $name)
    {

        parent::__construct(sprintf(
            'Namespace not specified in configuration, for provider %s',
            $name
        ));

    }

}