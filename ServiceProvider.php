<?php

namespace Codememory\Container\ServiceProvider;

use Codememory\Container\ServiceProvider\Exceptions\ProviderNotFoundException;
use Codememory\Container\ServiceProvider\Interfaces\ServiceProviderInterface;
use Codememory\Support\Arr;
use Generator;

/**
 * Class ServiceProvider
 *
 * @package Codememory\Container\ServiceProvider
 *
 * @author  Codememory
 */
class ServiceProvider implements ServiceProviderInterface
{

    /**
     * @var array
     */
    private array $providersForRegistration = [];

    /**
     * @var array
     */
    private array $providers = [];

    /**
     * @inheritDoc
     */
    public function register(string $name, string $class, ?callable $callback = null): ServiceProviderInterface
    {

        $injections = new Injections();
        $dataProvider = [
            $name => [
                'class'      => $class,
                'injections' => []
            ]
        ];

        if (null !== $callback) {
            call_user_func($callback, $injections);

            $dataProvider[$name] = array_merge($dataProvider[$name], $injections->getInjections());
        }

        $this->providersForRegistration[$name] = new Provider($this, $dataProvider);

        return $this;

    }

    /**
     * @inheritDoc
     * @throws ProviderNotFoundException
     */
    public function get(string $name): object
    {

        if (!$this->exist($name)) {
            throw new ProviderNotFoundException($name);
        }

        return $this->providers[$name]->__invoke();

    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {

        return $this->providers;

    }

    /**
     * @inheritDoc
     */
    public function exist(string $name): bool
    {

        return Arr::exists($this->providers, $name);

    }

    /**
     * @inheritDoc
     * @throws Exceptions\ProviderNamespaceNotSpecifiedException
     */
    public function makeRegistrationProviders(): void
    {

        $this->registrationProvidersFromConfiguration();

    }

    /**
     * @return void
     * @throws Exceptions\ProviderNamespaceNotSpecifiedException
     */
    private function registrationProvidersFromConfiguration(): void
    {

        $utils = new Utils();

        foreach ($utils->getProviders() as $name => $data) {
            $this->handlerProviderRegistration($name, $utils->getProvider($name));
        }

        $this->registrationProvidersInQueue();

    }

    /**
     * @return void
     */
    private function registrationProvidersInQueue(): void
    {

        foreach ($this->iterationProviders() as [$name, $provider]) {
            $this->providers[$name] = fn () => $provider->getProvider();
        }

    }

    /**
     * @return Generator
     */
    private function iterationProviders(): Generator
    {

        foreach ($this->providersForRegistration as $name => $provider) {
            yield [$name, $provider];
        }

    }

    /**
     * @param string $name
     * @param array  $fullDataProvider
     *
     * @return void
     */
    private function handlerProviderRegistration(string $name, array $fullDataProvider): void
    {

        $this->providersForRegistration[$name] = new Provider($this, $fullDataProvider);

    }

}

