<?php

namespace Codememory\Container\ServiceProvider\Interfaces;

/**
 * Interface ProviderInterface
 * @package Codememory\Container\ServiceProvider\Interfaces
 *
 * @author  Codememory
 */
interface ProviderInterface
{

    /**
     * @return object
     */
    public function getProvider(): object;

}