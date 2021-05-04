<?php

namespace Codememory\Container\ServiceProvider\Traits;

/**
 * Trait ReservedParametersValuesHandlerTrait
 * @package Codememory\Container\ServiceProvider\Traits
 *
 * @author  Codememory
 */
trait ReservedParametersValuesHandlerTrait
{

    /**
     * @param array $match
     *
     * @return mixed
     */
    private function reservedProvider(array $match): mixed
    {

        if(null !== $this->serviceProvider) {
            return $this->serviceProvider->get($match['value']);
        }

        return null;

    }

    /**
     * @param array $match
     *
     * @return object
     */
    private function reservedObject(array $match): object
    {

        return new $match['value']();

    }

}