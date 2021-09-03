<?php

namespace Codememory\Container\ServiceProvider;

use Codememory\Components\Configuration\Configuration;
use Codememory\Components\Configuration\Interfaces\ConfigInterface;
use Codememory\Components\GlobalConfig\GlobalConfig;
use Codememory\Container\ServiceProvider\Exceptions\ProviderNamespaceNotSpecifiedException;
use Codememory\Support\Arr;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class Utils
 * @package Codememory\Container\ServiceProvider
 *
 * @author  Codememory
 */
class Utils
{

    public const INJECTIONS = ['construct', 'method', 'property'];
    private const DEFAULT_AUTOWRITE = false;

    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * Utils constructor.
     */
    public function __construct()
    {

        $this->config = Configuration::getInstance()->open(GlobalConfig::get('serviceProvider.configName'));

    }

    /**
     * @return array
     */
    public function getProviders(): array
    {

        return $this->config->all();

    }

    /**
     * @param string $name
     *
     * @return array
     * @throws ProviderNamespaceNotSpecifiedException
     */
    public function getDataProvider(string $name): array
    {

        if ($this->has($name)) {
            $provider = $this->getProviders()[$name];
            $namespace = $provider['class'] ?? throw new ProviderNamespaceNotSpecifiedException($name);
            [$constructArgs, $constructAutowrite] = $this->injectionArguments($provider, 'construct');
            [$propertyArgs, $propertyAutowrite] = $this->injectionArguments($provider, 'property');
            [$methodArgs, $methodAutowrite] = $this->injectionArguments($provider, 'method');

            return $this->getProviderDataStructure(
                $namespace,
                $constructArgs,
                $propertyArgs,
                $methodArgs,
                array_combine(self::INJECTIONS, [$constructAutowrite, $propertyAutowrite, $methodAutowrite])
            );
        }

        return [];

    }

    /**
     * @param string $name
     *
     * @return array[]
     * @throws ProviderNamespaceNotSpecifiedException
     */
    public function getProvider(string $name): array
    {

        return [$name => $this->getDataProvider($name)];

    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {

        return Arr::exists($this->getProviders(), $name);

    }

    /**
     * @param string $namespace
     * @param array  $constructArguments
     * @param array  $propertyArguments
     * @param array  $methodArguments
     * @param array  $autowrite
     *
     * @return array
     */
    #[ArrayShape(['class' => "string", 'injections' => "array[]"])]
    public function getProviderDataStructure(string $namespace, array $constructArguments, array $propertyArguments, array $methodArguments, array $autowrite): array
    {

        $structure = [
            'class'      => $namespace,
            'injections' => [
                'construct' => $constructArguments,
                'property'  => $propertyArguments,
                'method'    => $methodArguments
            ]
        ];

        foreach (self::INJECTIONS as $injection) {
            $structure['injections'][$injection]['_autowrite'] = $autowrite[$injection];
        }

        return $structure;

    }

    /**
     * @param array  $provider
     * @param string $typeInjection
     *
     * @return array
     */
    private function injectionArguments(array $provider, string $typeInjection): array
    {

        $arguments = $provider[$typeInjection] ?? [];
        $arguments['_autowrite'] = $arguments['_autowrite'] ?? self::DEFAULT_AUTOWRITE;
        $autowrite = $arguments['_autowrite'];

        unset($arguments['_autowrite']);

        return [$arguments, $autowrite];

    }

}