<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Module
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Module;

use phpOMS\Application\ApplicationAbstract;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\System\MimeType;

/**
 * Module abstraction class.
 *
 * @package phpOMS\Module
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 *
 * @todo Orange-Management/Modules#113
 *  Don't use name but id for identification
 */
abstract class ModuleAbstract
{
    /**
     * Module name.
     *
     * @var string
     * @since 1.0.0
     */
    public const MODULE_NAME = '';

    /**
     * Module path.
     *
     * @var string
     * @since 1.0.0
     */
    public const MODULE_PATH = __DIR__ . '/../../Modules';

    /**
     * Module version.
     *
     * @var string
     * @since 1.0.0
     */
    public const MODULE_VERSION = '1.0.0';

    /**
     * Module id.
     *
     * @var string
     * @since 1.0.0
     */
    public const MODULE_ID = 0;

    /**
     * Receiving modules from?
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static array $providing = [];

    /**
     * Dependencies.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static array $dependencies = [];

    /**
     * Receiving modules from?
     *
     * @var string[]
     * @since 1.0.0
     */
    protected array $receiving = [];

    /**
     * Application instance.
     *
     * @var ApplicationAbstract
     * @since 1.0.0
     */
    protected ApplicationAbstract $app;

    /**
     * Constructor.
     *
     * @param null|ApplicationAbstract $app Application instance
     *
     * @since 1.0.0
     */
    public function __construct(ApplicationAbstract $app = null)
    {
        $this->app = $app ?? new class() extends ApplicationAbstract {};
    }

    /**
     * Get language files.
     *
     * @param string $language    Language key
     * @param string $destination Application destination (e.g. Backend)
     *
     * @return array<string, array<string, string>>
     *
     * @since 1.0.0
     */
    public static function getLocalization(string $language, string $destination) : array
    {
        $lang = [];
        if (\file_exists($oldPath = __DIR__ . '/../../Modules/' . static::MODULE_NAME . '/Theme/' . $destination . '/Lang/' . $language . '.lang.php')) {
            /** @noinspection PhpIncludeInspection */
            return include $oldPath;
        }

        return $lang;
    }

    /**
     * Add modules this module receives from
     *
     * @param string $module Module name
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addReceiving(string $module) : void
    {
        $this->receiving[] = $module;
    }

    /**
     * Get modules this module is receiving from
     *
     * @return string[]
     *
     * @since 1.0.0
     */
    public function getReceiving() : array
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return $this->receiving;
    }

    /**
     * Get modules this module is providing for
     *
     * @return string[]
     *
     * @since 1.0.0
     */
    public function getProviding() : array
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return static::$providing;
    }

    /**
     * Get the name of the module
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getName() : string
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return static::MODULE_NAME;
    }

    /**
     * Get module dependencies
     *
     * @return string[]
     *
     * @since 1.0.0
     */
    public function getDependencies() : array
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return static::$dependencies;
    }

    /**
     * Fills the response object
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param string           $status   Response status
     * @param string           $title    Response title
     * @param string           $message  Response message
     * @param mixed            $obj      Response object
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected function fillJsonResponse(
        RequestAbstract $request,
        ResponseAbstract $response,
        string $status,
        string $title,
        string $message,
        $obj
    ) : void
    {
        $response->getHeader()->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        $response->set($request->getUri()->__toString(), [
            'status'   => $status,
            'title'    => $title,
            'message'  => $message,
            'response' => $obj,
        ]);
    }

    /**
     * Fills the response object
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $obj      Response object
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected function fillJsonRawResponse(RequestAbstract $request, ResponseAbstract $response, $obj) : void
    {
        $response->getHeader()->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        $response->set($request->getUri()->__toString(), $obj);
    }

    /**
     * Create a model
     *
     * @param int    $account Account id
     * @param mixed  $obj     Response object
     * @param string $mapper  Object mapper
     * @param string $trigger Trigger for the event manager
     * @param string $ip      Ip
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected function createModel(int $account, $obj, string $mapper, string $trigger, string $ip) : void
    {
        $this->app->eventManager->trigger('PRE:Module:' . static::MODULE_NAME . '-' . $trigger . '-create', '', $obj);
        $mapper::create($obj);
        $this->app->eventManager->trigger('POST:Module:' . static::MODULE_NAME . '-' . $trigger . '-create', '', [
            $account,
            null, $obj,
            0, 0,
            static::MODULE_NAME,
            $ip
        ]);
    }

    /**
     * Create a model
     *
     * @param int    $account Account id
     * @param array  $objs    Response object
     * @param string $mapper  Object mapper
     * @param string $trigger Trigger for the event manager
     * @param string $ip      Ip
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected function createModels(int $account, array $objs, string $mapper, string $trigger, string $ip) : void
    {
        foreach ($objs as $obj) {
            $this->app->eventManager->trigger('PRE:Module:' . static::MODULE_NAME . '-' . $trigger . '-create', '', $obj);
            $mapper::create($obj);
            $this->app->eventManager->trigger('POST:Module:' . static::MODULE_NAME . '-' . $trigger . '-create', '', [
                $account,
                null, $obj,
                0, 0,
                static::MODULE_NAME,
                $ip
            ]);
        }
    }

    /**
     * Update a model
     *
     * @param int             $account Account id
     * @param mixed           $old     Response object old
     * @param mixed           $new     Response object new
     * @param \Closure|string $mapper  Object mapper
     * @param string          $trigger Trigger for the event manager
     * @param string          $ip      Ip
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected function updateModel(int $account, $old, $new, $mapper, string $trigger, string $ip) : void
    {
        $this->app->eventManager->trigger('PRE:Module:' . static::MODULE_NAME . '-' . $trigger . '-update', '', $old);
        if (\is_string($mapper)) {
            $mapper::update($new);
        } elseif ($mapper instanceof \Closure) {
            $mapper();
        }
        $this->app->eventManager->trigger('POST:Module:' . static::MODULE_NAME . '-' . $trigger . '-update', '', [
            $account,
            $old, $new,
            0, 0,
            static::MODULE_NAME,
            $ip
        ]);
    }

    /**
     * Delete a model
     *
     * @param int    $account Account id
     * @param mixed  $obj     Response object
     * @param string $mapper  Object mapper
     * @param string $trigger Trigger for the event manager
     * @param string $ip      Ip
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected function deleteModel(int $account, $obj, string $mapper, string $trigger, string $ip) : void
    {
        $this->app->eventManager->trigger('PRE:Module:' . static::MODULE_NAME . '-' . $trigger . '-delete', '', $obj);
        $mapper::delete($obj);
        $this->app->eventManager->trigger('POST:Module:' . static::MODULE_NAME . '-' . $trigger . '-delete', '', [
            $account,
            $obj,  null,
            0, 0,
            static::MODULE_NAME,
            $ip
        ]);
    }

    /**
     * Create a model relation
     *
     * @param int    $account Account id
     * @param mixed  $rel1    Object relation1
     * @param mixed  $rel2    Object relation2
     * @param string $mapper  Object mapper
     * @param string $field   Relation field
     * @param string $trigger Trigger for the event manager
     * @param string $ip      Ip
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected function createModelRelation(int $account, $rel1, $rel2, string $mapper, string $field, string $trigger, string $ip) : void
    {
        $this->app->eventManager->trigger('PRE:Module:' . static::MODULE_NAME . '-' . $trigger . '-relation', '', $rel1);
        $mapper::createRelation($field, $rel1, $rel2);
        $this->app->eventManager->trigger('POST:Module:' . static::MODULE_NAME . '-' . $trigger . '-relation', '', [
            $account,
            $rel1, $rel2,
            0, 0,
            static::MODULE_NAME,
            $ip
        ]);
    }
}
