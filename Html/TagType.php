<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Html;

use phpOMS\Datatypes\Enum;

/**
 * Tag type enum.
 *
 * @category   Framework
 * @package    phpOMS\Html
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class TagType extends Enum
{
    /* public */ const INPUT = 0; /* <input> */
    /* public */ const BUTTON = 1; /* <button> */
    /* public */ const LINK = 2; /* <a> */
    /* public */ const SYMMETRIC = 3; /* <span><div>... */
    /* public */ const TEXTAREA = 4; /* <textarea> */
    /* public */ const SELECT = 5; /* <select> */
    /* public */ const LABEL = 6; /* <label> */
    /* public */ const ULIST = 7; /* <ul> */
    /* public */ const OLIST = 8; /* <ul> */
}
