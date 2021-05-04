<?php

namespace Codememory\Container\ServiceProvider\Interfaces;

/**
 * Interface InjectionProviderInterface
 * @package Codememory\Container\ServiceProvider\Interfaces
 *
 * @author  Codememory
 */
interface InjectionProviderInterface
{

    /**
     * @param array $parameters
     * @param bool  $autowrite
     *
     * @return InjectionProviderInterface
     */
    public function construct(array $parameters, bool $autowrite = false): InjectionProviderInterface;

    /**
     * @param string $name
     * @param mixed  $value
     * @param bool   $autowrite
     *
     * @return InjectionProviderInterface
     */
    public function property(string $name, mixed $value, bool $autowrite = false): InjectionProviderInterface;

    /**
     * @param string $name
     * @param array  $parameters
     * @param bool   $autowrite
     *
     * @return InjectionProviderInterface
     */
    public function method(string $name, array $parameters, bool $autowrite = false): InjectionProviderInterface;


}