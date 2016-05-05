<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Html;

use phpOMS\Datatypes\Enum;

/**
 * Tag type enum.
 *
 * @category   Framework
 * @package    phpOMS\Html
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class TagType extends Enum
{
    const INPUT = 0; /* <input> */
    const BUTTON = 1; /* <button> */
    const LINK = 2; /* <a> */
    const SYMMETRIC = 3; /* <span><div>... */
    const TEXTAREA = 4; /* <textarea> */
    const SELECT = 5; /* <select> */
    const LABEL = 6; /* <label> */
    const ULIST = 7; /* <ul> */
    const OLIST = 8; /* <ul> */
}
