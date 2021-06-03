<?php

namespace Codememory\Container\ServiceProvider;

use Codememory\Container\DependencyInjection\DependencyInjection;
use Codememory\Container\DependencyInjection\Exceptions\NoValidNamespaceException;
use Codememory\Container\DependencyInjection\Interfaces\DependencyInjectionInterface;
use Codememory\Container\DependencyInjection\Interfaces\InjectionInterface;
use Codememory\Container\ServiceProvider\Interfaces\ProviderInterface;
use Codememory\Container\ServiceProvider\Interfaces\ServiceProviderInterface;

/**
 * Class Provider
 * @package Codememory\Container\ServiceProvider
 *
 * @author  Codememory
 */
class Provider implements ProviderInterface
{

    /**
     * @var array
     */
    private array $data;

    /**
     * @var ServiceProviderInterface
     */
    private ServiceProviderInterface $serviceProvider;

    /**
     * Provider constructor.
     *
     * @param ServiceProviderInterface $serviceProvider
     * @param array                    $dataProvider
     */
    public function __construct(ServiceProviderInterface $serviceProvider, array $dataProvider)
    {

        $this->serviceProvider = $serviceProvider;
        $this->data = $dataProvider;

    }

    /**
     * @return object
     * @throws NoValidNamespaceException
     */
    public function getProvider(): object
    {

        $repository = new ProviderRepository($this->data);

        return $this->handle($repository)->get($repository->getName());

    }

    /**
     * @param ProviderRepository $repository
     *
     * @return DependencyInjectionInterface
     * @throws NoValidNamespaceException
     */
    private function handle(ProviderRepository $repository): DependencyInjectionInterface
    {

        $di = new DependencyInjection();

        return $di->add($repository->getName(), $repository->getNamespace(), function (InjectionInterface $injection) use ($repository) {
            $constructArguments = $repository->getInjectionArguments('construct');

            if ([] !== $constructArguments) {
                $injection->construct($this->getProcessedParameterValue($constructArguments));
            }

            $this
                ->iterationParamsMethodAndProperty('property', $injection, $repository)
                ->iterationParamsMethodAndProperty('method', $injection, $repository);
        });

    }

    /**
     * @param string             $typeInjection
     * @param InjectionInterface $injection
     * @param ProviderRepository $repository
     *
     * @return Provider
     */
    private function iterationParamsMethodAndProperty(string $typeInjection, InjectionInterface $injection, ProviderRepository $repository): Provider
    {

        foreach ($repository->getInjectionArguments($typeInjection) as $propertyOrMethod => $arguments) {
            $injection->$typeInjection($propertyOrMethod, $this->getProcessedParameterValue($arguments), $repository->getAutowriteInjection($typeInjection));
        }

        return $this;

    }

    /**
     * @param mixed $parameter
     *
     * @return mixed
     */
    private function getProcessedParameterValue(mixed $parameter): mixed
    {

        if (is_array($parameter)) {
            foreach ($parameter as &$value) {
                $value = $this->getObjectParameterValue($value)->getParameter();
            }
        } else {
            $parameter = $this->getObjectParameterValue($parameter)->getParameter();
        }

        return $parameter;

    }

    /**
     * @param mixed $parameter
     *
     * @return ParameterValue
     */
    private function getObjectParameterValue(mixed $parameter): ParameterValue
    {

        $parameterValue = new ParameterValue($parameter);
        $parameterValue->setServiceProvider($this->serviceProvider);

        return $parameterValue;

    }

}