<?php

namespace Codememory\Container\ServiceProvider\Interfaces;

/**
 * Interface ServiceProviderInterface
 * @package Codememory\Container\ServiceProvider\Interfaces
 *
 * @author  Codememory
 */
interface ServiceProviderInterface
{

    /**
     * @param string        $name
     * @param string        $class
     * @param callable|null $callback
     *
     * @return ServiceProviderInterface
     */
    public function register(string $name, string $class, ?callable $callback = null): ServiceProviderInterface;

    /**
     * @param string $name
     *
     * @return object
     */
    public function get(string $name): object;

    /**
     * @return array
     */
    public function all(): array;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function exist(string $name): bool;

    /**
     * @return void
     */
    public function makeRegistrationProviders(): void;

}