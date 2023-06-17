<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Ai\NeuralNetwork
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Ai\NeuralNetwork;

/**
 * Neuron
 *
 * @package phpOMS\Ai\NeuralNetwork
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Neuron
{
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

    /**
     * Add neuron input
     *
     * @param mixed $input  Input
     * @param float $weight Weight of input
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addInput(mixed $input, float $weight) : void
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
        $length = \count($this->inputs);
        $output = 0.0;

        for ($i = 0; $i < $length; ++$i) {
            $output += $this->inputs[$i]->output() * $this->weights[$i];
        }

        return $output + $this->bias;
        // return $this->activationFunction($output + $this->bias);
    }
}
