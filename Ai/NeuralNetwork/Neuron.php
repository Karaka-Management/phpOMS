<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Ai\NeuralNetwork
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Ai\NeuralNetwork;

/**
 * Neuron
 *
 * @package phpOMS\Ai\NeuralNetwork
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Neuron {
    /**
     * Neuron inputs
     *
     * @var array
     * @since 1.0.0
     */
    private array $inputs = [];

    /**
     * Input weights
     *
     * @var array
     * @since 1.0.0
     */
    private array $weights = [];

    /**
     * Bias
     *
     * @var float
     * @since 1.0.0
     */
    public float $bias = 0;

    /**
     * Constructor.
     *
     * @param array $inputs  Neuron inputs/connections
     * @param array $weights Input weights
     * @param float $bias    Input bias
     *
     * @since 1.0.0
     */
    public function __construct(array $inputs = [], array $weights = [], float $bias = 0.0)
    {
        $this->inputs  = $inputs;
        $this->weights = $weights;
        $this->bias    = $bias;
    }

    public function addInput($input, float $weight) : void
    {
        $this->inputs[]  = $input;
        $this->weights[] = $weight;
    }

    /**
     * Create node output
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function output() : float
    {
        $length = \count($this->intputs);
        $output = 0.0;

        for ($i = 0; $i < $length; ++$i) {
            $output += $this->inputs[$i]->output() * $this->weights[$i];
        }

        return $output + $this->bias;
        // return $this->activationFunction($output + $this->bias);
    }
}
