<?php


namespace Codememory\Container\ServiceProvider;

use JetBrains\PhpStorm\Pure;

/**
 * Class ProviderRepository
 * @package Codememory\Container\ServiceProvider
 *
 * @author  Codememory
 */
class ProviderRepository
{

    /**
     * @var array
     */
    private array $data;

    /**
     * ProviderRepository constructor.
     *
     * @param array $dataProvider
     */
    public function __construct(array $dataProvider)
    {

        $this->data = $dataProvider;

    }

    /**
     * @return string
     */
    public function getName(): string
    {

        return array_key_last($this->data);

    }

    /**
     * @return string|null
     */
    #[Pure] public function getNamespace(): ?string
    {

        return $this->data[$this->getName()]['class'];

    }

    /**
     * @return array
     */
    #[Pure] public function getInjections(): array
    {

        return $this->data[$this->getName()]['injections'];

    }

    /**
     * @param string $injection
     *
     * @return mixed
     */
    public function getInjectionArguments(string $injection): array
    {

        $injection = $this->getInjections()[$injection] ?? [];

        unset($injection['_autowrite']);

        return $injection;

    }

    /**
     * @param string $injection
     *
     * @return bool
     */
    #[Pure] public function getAutowriteInjection(string $injection): bool
    {

        return $this->getInjections()[$injection]['_autowrite'];

    }

}