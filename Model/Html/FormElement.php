<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Model\Html
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Model\Html;

/**
 * Form element class.
 *
 * @package phpOMS\Model\Html
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class FormElement
{
    /**
     * Element id.
     *
     * @var string
     * @since 1.0.0
     */
    public string $id;

    /**
     * Form id.
     *
     * @var string
     * @since 1.0.0
     */
    public string $form;

    /**
     * Element name.
     *
     * @var string
     * @since 1.0.0
     */
    public string $name;

    /**
     * Value is required.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $required;

    /**
     * Default value.
     *
     * @var string
     * @since 1.0.0
     */
    public string $defaultValue;

    /**
     * Required values which cannot be changed/removed.
     *
     * @var string
     * @since 1.0.0
     */
    public string $requiredValue;

    /**
     * Autosave on change.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $autosave;

    /**
     * Constructor.
     *
     * @param string $id            Element id
     * @param string $form          Form id
     * @param string $name          Element name
     * @param bool   $required      Value is required
     * @param string $defaultValue  Default value
     * @param string $requiredValue Values which cannot be removed/changed
     * @param bool   $autosave      Save on change
     *
     * @since 1.0.0
     */
    public function __construct(
        string $id = '',
        string $form = '',
        string $name = '',
        bool $required = false,
        string $defaultValue = '',
        string $requiredValue = '',
        bool $autosave = false
    ) {
        $this->id            = $id;
        $this->form          = $form;
        $this->name          = $name;
        $this->required      = $required;
        $this->defaultValue  = $defaultValue;
        $this->requiredValue = $requiredValue;
        $this->autosave      = $autosave;
    }
}
