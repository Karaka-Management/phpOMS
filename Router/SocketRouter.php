<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Router
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Router;

use phpOMS\Account\Account;

/**
 * Router class for socket routes.
 *
 * @package phpOMS\Router
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class SocketRouter implements RouterInterface
{
    /**
     * Routes.
     *
     * @var array<string, array<int, array{dest:mixed, verb:int, csrf?:bool, active?:bool, permission?:array{module:string, type:int, category:int}, validation?:?array, pattern?:?string}>>
     * @since 1.0.0
     */
    private array $routes = [];

    /**
     * Add routes from file.
     *
     * Files need to return a php array of the following structure (see PermissionHandlingTrait):
     * return [
     *      '{REGEX_PATH}' => [
     *          [
     *              'dest' => '{DESTINATION_NAMESPACE:method}', // use :: for static functions
     *              'verb' => RouteVerb::{VERB},
     *              'csrf' => true,
     *              'permission' => [ // optional
     *                  'module' => '{NAME}',
     *                  'type' => PermissionType::{TYPE},
     *                  'category' => PermissionCategory::{STATE},
     *              ],
     *          ],
     *          // define different destination for different verb
     *      ]
     *      // define another regex path here
     * ];
     *
     * @param string $path Route file path
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function importFromFile(string $path) : bool
    {
        if (!\is_file($path)) {
            return false;
        }

        /** @noinspection PhpIncludeInspection */
        $this->routes += include $path;

        return true;
    }

    /**
     * Clear routes
     *
     * @return void
     * @since 1.0.0
     */
    public function clear() : void
    {
        $this->routes = [];
    }

    /**
     * {@inheritdoc}
     */
    public function add(
        string $route,
        mixed $destination,
        int $verb = RouteVerb::GET,
        bool $csrf = false,
        array $validation = [],
        string $dataPattern = ''
    ) : void
    {
        if (!isset($this->routes[$route])) {
            $this->routes[$route] = [];
        }

        $this->routes[$route][] = [
            'dest'       => $destination,
            'verb'       => $verb,
            'csrf'       => $csrf,
            'validation' => empty($validation) ? null : $validation,
            'pattern'    => empty($dataPattern) ? null : $dataPattern,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function route(
        string $uri,
        ?string $csrf = null,
        int $verb = RouteVerb::GET,
        ?int $app = null,
        ?int $unitId = null,
        ?Account $account = null,
        ?array $data = null
    ) : array
    {
        $bound = [];
        foreach ($this->routes as $route => $destination) {
            if (!((bool) \preg_match('~' . $route . '~', $uri))) {
                continue;
            }

            foreach ($destination as $d) {
                if (!($d['active'] ?? true)) {
                    continue;
                }

                if ((!isset($d['verb']) || $d['verb'] === RouteVerb::ANY)
                    || $verb === RouteVerb::ANY
                    || ($verb & $d['verb']) === $verb
                ) {
                    // if csrf is required but not set
                    if (($d['csrf'] ?? false) && $csrf === null) {
                        return ['dest' => RouteStatus::INVALID_CSRF];
                    }

                    // if permission check is invalid
                    if (!empty($d['permission'] ?? null)
                        && ($account === null || $account->id === 0)
                    ) {
                        return ['dest' => RouteStatus::NOT_LOGGED_IN];
                    } elseif (!empty($d['permission'] ?? null)
                        && !($account?->hasPermission(
                                $d['permission']['type'] ?? 0,
                                $d['permission']['unit'] ?? $unitId,
                                $app,
                                $d['permission']['module'] ?? null,
                                $d['permission']['category'] ?? null
                            )
                        )
                    ) {
                        return ['dest' => RouteStatus::INVALID_PERMISSIONS];
                    }

                    // if validation check is invalid
                    if (isset($d['validation'])) {
                        foreach ($d['validation'] as $name => $pattern) {
                            if (!isset($data[$name]) || \preg_match($pattern, $data[$name]) !== 1) {
                                return ['dest' => RouteStatus::INVALID_DATA];
                            }
                        }
                    }

                    $temp = ['dest' => $d['dest']];

                    // fill data
                    if (isset($d['pattern'])) {
                        \preg_match($d['pattern'], $uri, $matches);

                        $temp['data'] = $matches;
                    }

                    $bound[] = $temp;
                }
            }
        }

        return $bound;
    }
}
