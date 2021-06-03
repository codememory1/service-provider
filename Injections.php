<?php

namespace Codememory\Container\ServiceProvider;

use Codememory\Container\ServiceProvider\Interfaces\InjectionProviderInterface;

/**
 * Class Injections
 * @package Codememory\Container\ServiceProvider
 */
class Injections implements InjectionProviderInterface
{

    /**
     * @var array
     */
    private array $injections = [];

    /**
     * @inheritDoc
     */
    public function construct(array $parameters, bool $autowrite = false): InjectionProviderInterface
    {

        return $this->setInjection('construct', $parameters, $autowrite);

    }

    /**
     * @inheritDoc
     */
    public function property(string $name, mixed $value, bool $autowrite = false): InjectionProviderInterface
    {

        return $this->setInjection('property', [$name => $value], $autowrite);

    }

    /**
     * @inheritDoc
     */
    public function method(string $name, array $parameters, bool $autowrite = false): InjectionProviderInterface
    {

        return $this->setInjection('method', [$name => $parameters], $autowrite);

    }

    /**
     * @param string $injection
     * @param array  $data
     * @param bool   $autowrite
     *
     * @return InjectionProviderInterface
     */
    public function setInjection(string $injection, array $data, bool $autowrite = false): Injections
    {

        $data = array_merge($data, ['_autowrite' => $autowrite]);

        $this->injections['injections'][$injection] = $data;

        return $this;

    }

    /**
     * @return array
     */
    public function getInjections(): array
    {

        return $this->injections;

    }

}