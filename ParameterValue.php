<?php

namespace Codememory\Container\ServiceProvider;

use Codememory\Container\ServiceProvider\Interfaces\ServiceProviderInterface;
use Codememory\Container\ServiceProvider\Traits\ReservedParametersValuesHandlerTrait;

/**
 * Class ParameterValue
 * @package Codememory\Container\ServiceProvider
 *
 * @author  Codememory
 */
class ParameterValue
{

    use ReservedParametersValuesHandlerTrait;

    private const RESERVED_VALUE_METHODS = [
        'provider' => '/^@provider=(?<value>[a-z0-9\.]+)/i',
        'object'   => '/^@object=(?<value>.+)/i'
    ];

    /**
     * @var mixed
     */
    private mixed $parameter;

    /**
     * @var ServiceProviderInterface|null
     */
    private ?ServiceProviderInterface $serviceProvider = null;

    /**
     * ParameterValue constructor.
     *
     * @param mixed $parameterValue
     */
    public function __construct(mixed $parameterValue)
    {

        $this->parameter = $parameterValue;

    }

    /**
     * @param ServiceProviderInterface $serviceProvider
     *
     * @return $this
     */
    public function setServiceProvider(ServiceProviderInterface $serviceProvider): ParameterValue
    {

        $this->serviceProvider = $serviceProvider;

        return $this;

    }

    /**
     * @return mixed
     */
    public function getParameter(): mixed
    {

        return $this->handler();

    }

    /**
     * @return mixed
     */
    private function handler(): mixed
    {

        foreach (self::RESERVED_VALUE_METHODS as $method => $regex) {
            if (is_string($this->parameter) && preg_match($regex, $this->parameter, $match)) {
                $this->parameter = call_user_func_array([$this, sprintf('reserved%s', ucfirst($method))], [$match]);
            }
        }

        return $this->parameter;

    }

}