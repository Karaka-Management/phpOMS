<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
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
use phpOMS\Utils\StringUtils;

/**
 * Module abstraction class.
 *
 * @method __call(string $name, array $arguments)
 *
 * @package phpOMS\Module
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class ModuleAbstract
{
    /**
     * Module name.
     *
     * @var string
     * @since 1.0.0
     */
    public const NAME = '';

    /**
     * Module path.
     *
     * @var string
     * @since 1.0.0
     */
    public const PATH = __DIR__ . '/../../Modules/';

    /**
     * Module version.
     *
     * @var string
     * @since 1.0.0
     */
    public const VERSION = '1.0.0';

    /**
     * Module id.
     *
     * @var string
     * @since 1.0.0
     */
    public const ID = 0;

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
        if (\is_file($oldPath = static::PATH . static::NAME . '/Theme/' . $destination . '/Lang/' . $language . '.lang.php')) {
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
        return static::NAME;
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
        $response->header->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        $response->set($request->uri->__toString(), [
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
    protected function fillJsonRawResponse(RequestAbstract $request, ResponseAbstract $response, mixed $obj) : void
    {
        $response->header->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        $response->set($request->uri->__toString(), $obj);
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
    protected function createModel(int $account, mixed $obj, string $mapper, string $trigger, string $ip) : void
    {
        $trigger = static::NAME . '-' . $trigger . '-create';

        $this->app->eventManager->triggerSimilar('PRE:Module:' . $trigger, '', $obj);
        $id = $mapper::create($obj);
        $this->app->eventManager->triggerSimilar('POST:Module:' . $trigger, '',
            [
                $account,
                null, $obj,
                StringUtils::intHash($mapper), $trigger,
                static::NAME,
                (string) $id,
                '',
                $ip,
            ]
        );
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
        $trigger = static::NAME . '-' . $trigger . '-create';

        foreach ($objs as $obj) {
            $this->app->eventManager->triggerSimilar('PRE:Module:' . $trigger, '', $obj);
            $id = $mapper::create($obj);
            $this->app->eventManager->triggerSimilar('POST:Module:' . $trigger, '',
                [
                    $account,
                    null, $obj,
                    StringUtils::intHash($mapper), $trigger,
                    static::NAME,
                    (string) $id,
                    '',
                    $ip,
                ]
            );
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
    protected function updateModel(int $account, mixed $old, mixed $new, string | \Closure $mapper, string $trigger, string $ip) : void
    {
        $this->app->eventManager->triggerSimilar('PRE:Module:' . static::NAME . '-' . $trigger . '-update', '', $old);
        $id = 0;

        if (\is_string($mapper)) {
            $id = $mapper::update($new);
        } else {
            $mapper();
        }

        $this->app->eventManager->triggerSimilar('POST:Module:' . static::NAME . '-' . $trigger . '-update', '',
            [
                $account,
                $old, $new,
                StringUtils::intHash(\is_string($mapper) ? $mapper : \get_class($mapper)), $trigger,
                static::NAME,
                (string) $id,
                '',
                $ip,
            ]
        );
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
    protected function deleteModel(int $account, mixed $obj, string $mapper, string $trigger, string $ip) : void
    {
        $trigger = static::NAME . '-' . $trigger . '-delete';

        $this->app->eventManager->triggerSimilar('PRE:Module:' . $trigger, '', $obj);
        $id = $mapper::delete($obj);
        $this->app->eventManager->triggerSimilar('POST:Module:' . $trigger, '',
            [
                $account,
                $obj,  null,
                StringUtils::intHash($mapper), $trigger,
                static::NAME,
                (string) $id,
                '',
                $ip,
            ]
        );
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
    protected function createModelRelation(int $account, mixed $rel1, mixed $rel2, string $mapper, string $field, string $trigger, string $ip) : void
    {
        $trigger = static::NAME . '-' . $trigger . '-relation-create';

        $this->app->eventManager->triggerSimilar('PRE:Module:' . $trigger, '', $rel1);
        $mapper::createRelation($field, $rel1, $rel2);
        $this->app->eventManager->triggerSimilar('POST:Module:' . $trigger, '',
            [
                $account,
                $rel1, $rel2,
                StringUtils::intHash($mapper), $trigger,
                static::NAME,
                '0',
                '',
                $ip,
            ]
        );
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
    protected function deleteModelRelation(int $account, mixed $rel1, mixed $rel2, string $mapper, string $field, string $trigger, string $ip) : void
    {
        $trigger = static::NAME . '-' . $trigger . '-relation-delete';

        $this->app->eventManager->triggerSimilar('PRE:Module:' . $trigger, '', $rel1);
        $mapper::deleteRelation($field, $rel1, $rel2);
        $this->app->eventManager->triggerSimilar('POST:Module:' . $trigger, '',
            [
                $account,
                $rel1, $rel2,
                StringUtils::intHash($mapper), $trigger,
                static::NAME,
                '0',
                '',
                $ip,
            ]
        );
    }
}
