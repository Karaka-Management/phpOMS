<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Auth
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Auth;

use phpOMS\DataStorage\Session\SessionInterface;

/**
 * Auth class.
 *
 * Responsible for authenticating and initializing the connection
 *
 * @package phpOMS\Auth
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class Auth
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Authenticates user.
     *
     * @param SessionInterface $session Session
     *
     * @return int Returns the user id
     *
     * @since 1.0.0
     */
    public static function authenticate(SessionInterface $session) : int
    {
        $uid = $session->get('UID');

        return empty($uid) ? 0 : $uid;
    }

    /**
     * Logout the given user.
     *
     * @param SessionInterface $session Session
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function logout(SessionInterface $session) : void
    {
        $session->remove('UID');
    }
}
