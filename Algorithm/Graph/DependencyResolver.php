<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Algorithm\Graph;
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Graph;

/**
 * Dependency resolver class.
 *
 * @package phpOMS\Algorithm\Graph;
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class DependencyResolver
{
    /**
     * Resolve dependencies
     *
     * @param array $graph Graph to resolve
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    public static function resolve(array $graph) : ?array
    {
        $resolved   = [];
        $unresolved = [];
        foreach ($graph as $table => $dependency) {
            self::dependencyResolve($table, $graph, $resolved, $unresolved);
        }

        return !empty($unresolved) ? null : $resolved;
    }

    /**
     * Algorithm to resolve dependencies
     *
     * @param int|string               $item  Item id
     * @param array<int|string, array> $items All items
     *
     * @return void
     *
     * @since 1.0.0
     */
    private static function dependencyResolve(int | string $item, array $items, array &$resolved, array &$unresolved) : void
    {
        $unresolved[] = $item;

        if (!isset($items[$item])) {
            return;
        }

        foreach ($items[$item] as $dependency) {
            if (!\in_array($dependency, $unresolved)) {
                $unresolved[] = $dependency;
                self::dependencyResolve($dependency, $items, $resolved, $unresolved);
            } else {
                continue; // circular dependency
            }
        }

        if (!\in_array($item, $resolved)) {
            $resolved[] = $item;
        }

        foreach ($unresolved as $key => $unres) {
            if ($unres === $item) {
                unset($unresolved[$key]);
            }
        }
    }
}
