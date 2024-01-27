<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Module
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Module;

use phpOMS\Application\ApplicationAbstract;
use phpOMS\Message\NotificationLevel;
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
 * @license OMS License 2.0
 * @link    https://jingga.app
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
     * @var int
     * @since 1.0.0
     */
    public const ID = 0;

    /**
     * Receiving modules from?
     *
     * @var string[]
     * @since 1.0.0
     */
    public static array $providing = [];

    /**
     * Dependencies.
     *
     * @var string[]
     * @since 1.0.0
     */
    public static array $dependencies = [];

    /**
     * Receiving modules from?
     *
     * @var string[]
     * @since 1.0.0
     */
    public array $receiving = [];

    /**
     * Application instance.
     *
     * @var ApplicationAbstract
     * @since 1.0.0
     */
    protected ApplicationAbstract $app;

    /**
     * Is module active.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $active = true;

    /**
     * Auditor for logging.
     *
     * @var null|\Modules\Auditor\Controller\ApiController
     * @since 1.0.0
     */
    public static ?ModuleAbstract $auditor = null;

    /**
     * Constructor.
     *
     * @param null|ApplicationAbstract $app Application instance
     *
     * @since 1.0.0
     */
    public function __construct(?ApplicationAbstract $app = null)
    {
        $this->app = $app ?? new class() extends ApplicationAbstract {};

        if (self::$auditor === null && static::ID !== 1006200000) {
            /** @phpstan-ignore-next-line */
            self::$auditor = $this->app->moduleManager?->get('Auditor', 'Api');
            self::$auditor = self::$auditor === null || self::$auditor::ID === 0 ? null : self::$auditor;
        }
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
        if (\is_file($oldPath = \rtrim(static::PATH, '/') . '/Theme/' . $destination . '/Lang/' . $language . '.lang.php')) {
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
     * The response object contains the following data:
     *
     *  * status = Response status
     *  * title = Response title (e.g. for frontend reporting)
     *  * message = Response message (e.g. for frontend reporting)
     *  * response = Response object (e.g. for validation/frontend reporting/form validation)
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
        mixed $obj
    ) : void
    {
        $response->header->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        $response->data[$request->uri->__toString()] = [
            'status'   => $status,
            'title'    => $title,
            'message'  => $message,
            'response' => $obj,
        ];
    }

    /**
     * Create standard model create response.
     *
     * The response object contains the following data:
     *
     *  * status = Response status
     *  * title = Response title (e.g. for frontend reporting)
     *  * message = Response message (e.g. for frontend reporting)
     *  * response = Response object (e.g. for validation/frontend reporting/form validation)
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $obj      Response object
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function createStandardCreateResponse(
        RequestAbstract $request,
        ResponseAbstract $response,
        mixed $obj
    ) : void
    {
        $response->header->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        $response->data[$request->uri->__toString()] = [
            'status'   => NotificationLevel::OK,
            'title'    => '',
            'message'  => $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'SuccessfulCreate'),
            'response' => $obj,
        ];
    }

    /**
     * Create standard model update response.
     *
     * The response object contains the following data:
     *
     *  * status = Response status
     *  * title = Response title (e.g. for frontend reporting)
     *  * message = Response message (e.g. for frontend reporting)
     *  * response = Response object (e.g. for validation/frontend reporting/form validation)
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $obj      Response object
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function createStandardUpdateResponse(
        RequestAbstract $request,
        ResponseAbstract $response,
        mixed $obj
    ) : void
    {
        $response->header->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        $response->data[$request->uri->__toString()] = [
            'status'   => NotificationLevel::OK,
            'title'    => '',
            'message'  => $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'SuccessfulUpdate'),
            'response' => $obj,
        ];
    }

    /**
     * Create standard model delete response.
     *
     * The response object contains the following data:
     *
     *  * status = Response status
     *  * title = Response title (e.g. for frontend reporting)
     *  * message = Response message (e.g. for frontend reporting)
     *  * response = Response object (e.g. for validation/frontend reporting/form validation)
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $obj      Response object
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function createStandardDeleteResponse(
        RequestAbstract $request,
        ResponseAbstract $response,
        mixed $obj
    ) : void
    {
        $response->header->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        $response->data[$request->uri->__toString()] = [
            'status'   => NotificationLevel::OK,
            'title'    => '',
            'message'  => $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'SuccessfulDelete'),
            'response' => $obj,
        ];
    }

    /**
     * Create standard model remove response.
     *
     * The response object contains the following data:
     *
     *  * status = Response status
     *  * title = Response title (e.g. for frontend reporting)
     *  * message = Response message (e.g. for frontend reporting)
     *  * response = Response object (e.g. for validation/frontend reporting/form validation)
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $obj      Response object
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function createStandardRemoveResponse(
        RequestAbstract $request,
        ResponseAbstract $response,
        mixed $obj
    ) : void
    {
        $response->header->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        $response->data[$request->uri->__toString()] = [
            'status'   => NotificationLevel::OK,
            'title'    => '',
            'message'  => $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'SuccessfulRemove'),
            'response' => $obj,
        ];
    }

    /**
     * Create standard model return response.
     *
     * The response object contains the following data:
     *
     *  * status = Response status
     *  * title = Response title (e.g. for frontend reporting)
     *  * message = Response message (e.g. for frontend reporting)
     *  * response = Response object (e.g. for validation/frontend reporting/form validation)
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $obj      Response object
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function createStandardReturnResponse(
        RequestAbstract $request,
        ResponseAbstract $response,
        mixed $obj
    ) : void
    {
        $response->header->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        $response->data[$request->uri->__toString()] = [
            'status'   => NotificationLevel::OK,
            'title'    => '',
            'message'  => $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'SuccessfulReturn'),
            'response' => $obj,
        ];
    }

    /**
     * Create standard model relation add response.
     *
     * The response object contains the following data:
     *
     *  * status = Response status
     *  * title = Response title (e.g. for frontend reporting)
     *  * message = Response message (e.g. for frontend reporting)
     *  * response = Response object (e.g. for validation/frontend reporting/form validation)
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $obj      Response object
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function createStandardAddResponse(
        RequestAbstract $request,
        ResponseAbstract $response,
        mixed $obj
    ) : void
    {
        $response->header->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        $response->data[$request->uri->__toString()] = [
            'status'   => NotificationLevel::OK,
            'title'    => '',
            'message'  => $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'SuccessfulAdd'),
            'response' => $obj,
        ];
    }

    /**
     * Create invalid model create response.
     *
     * The response object contains the following data:
     *
     *  * status = Response status
     *  * title = Response title (e.g. for frontend reporting)
     *  * message = Response message (e.g. for frontend reporting)
     *  * response = Response object (e.g. for validation/frontend reporting/form validation)
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $obj      Response object
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function createInvalidCreateResponse(
        RequestAbstract $request,
        ResponseAbstract $response,
        mixed $obj
    ) : void
    {
        $response->header->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        $response->data[$request->uri->__toString()] = [
            'status'   => NotificationLevel::WARNING,
            'title'    => '',
            'message'  => $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'InvalidCreate'),
            'response' => $obj,
        ];
    }

    /**
     * Create invalid model update response.
     *
     * The response object contains the following data:
     *
     *  * status = Response status
     *  * title = Response title (e.g. for frontend reporting)
     *  * message = Response message (e.g. for frontend reporting)
     *  * response = Response object (e.g. for validation/frontend reporting/form validation)
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $obj      Response object
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function createInvalidUpdateResponse(
        RequestAbstract $request,
        ResponseAbstract $response,
        mixed $obj
    ) : void
    {
        $response->header->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        $response->data[$request->uri->__toString()] = [
            'status'   => NotificationLevel::WARNING,
            'title'    => '',
            'message'  => $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'InvalidUpdate'),
            'response' => $obj,
        ];
    }

    /**
     * Create invalid model delete response.
     *
     * The response object contains the following data:
     *
     *  * status = Response status
     *  * title = Response title (e.g. for frontend reporting)
     *  * message = Response message (e.g. for frontend reporting)
     *  * response = Response object (e.g. for validation/frontend reporting/form validation)
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $obj      Response object
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function createInvalidDeleteResponse(
        RequestAbstract $request,
        ResponseAbstract $response,
        mixed $obj
    ) : void
    {
        $response->header->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        $response->data[$request->uri->__toString()] = [
            'status'   => NotificationLevel::WARNING,
            'title'    => '',
            'message'  => $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'InvalidDelete'),
            'response' => $obj,
        ];
    }

    /**
     * Create invalid model relation remove response.
     *
     * The response object contains the following data:
     *
     *  * status = Response status
     *  * title = Response title (e.g. for frontend reporting)
     *  * message = Response message (e.g. for frontend reporting)
     *  * response = Response object (e.g. for validation/frontend reporting/form validation)
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $obj      Response object
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function createInvalidRemoveResponse(
        RequestAbstract $request,
        ResponseAbstract $response,
        mixed $obj
    ) : void
    {
        $response->header->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        $response->data[$request->uri->__toString()] = [
            'status'   => NotificationLevel::WARNING,
            'title'    => '',
            'message'  => $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'InvalidRemove'),
            'response' => $obj,
        ];
    }

    /**
     * Create invalid model return response.
     *
     * The response object contains the following data:
     *
     *  * status = Response status
     *  * title = Response title (e.g. for frontend reporting)
     *  * message = Response message (e.g. for frontend reporting)
     *  * response = Response object (e.g. for validation/frontend reporting/form validation)
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $obj      Response object
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function createInvalidReturnResponse(
        RequestAbstract $request,
        ResponseAbstract $response,
        mixed $obj
    ) : void
    {
        $response->header->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        $response->data[$request->uri->__toString()] = [
            'status'   => NotificationLevel::WARNING,
            'title'    => '',
            'message'  => $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'InvalidReturn'),
            'response' => $obj,
        ];
    }

    /**
     * Create invalid model relation create response.
     *
     * The response object contains the following data:
     *
     *  * status = Response status
     *  * title = Response title (e.g. for frontend reporting)
     *  * message = Response message (e.g. for frontend reporting)
     *  * response = Response object (e.g. for validation/frontend reporting/form validation)
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $obj      Response object
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function createInvalidAddResponse(
        RequestAbstract $request,
        ResponseAbstract $response,
        mixed $obj
    ) : void
    {
        $response->header->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        $response->data[$request->uri->__toString()] = [
            'status'   => NotificationLevel::WARNING,
            'title'    => '',
            'message'  => $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'InvalidAdd'),
            'response' => $obj,
        ];
    }

    /**
     * Create invalid model permission response.
     *
     * The response object contains the following data:
     *
     *  * status = Response status
     *  * title = Response title (e.g. for frontend reporting)
     *  * message = Response message (e.g. for frontend reporting)
     *  * response = Response object (e.g. for validation/frontend reporting/form validation)
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $obj      Response object
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function createInvalidPermissionResponse(
        RequestAbstract $request,
        ResponseAbstract $response,
        mixed $obj
    ) : void
    {
        $response->header->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        $response->data[$request->uri->__toString()] = [
            'status'   => NotificationLevel::WARNING,
            'title'    => '',
            'message'  => $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'InvalidPermission'),
            'response' => $obj,
        ];
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
        $response->data[$request->uri->__toString()] = $obj;
    }

    /**
     * Create a model
     *
     *  1. Execute pre DB interaction event
     *  2. Create model in DB
     *  3. Execute post DB interaction event (e.g. generates an audit log)
     *
     * @param int               $account Account id
     * @param mixed             $obj     Response object
     * @param string | \Closure $mapper  Object mapper
     * @param string            $trigger Trigger for the event manager
     * @param string            $ip      Ip
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected function createModel(int $account, mixed $obj, string | \Closure $mapper, string $trigger, string $ip) : void
    {
        $trigger = static::NAME . '-' . $trigger . '-create';

        $this->app->eventManager->triggerSimilar('PRE:Module:' . $trigger, '', $obj);
        $id = 0;

        if (\is_string($mapper)) {
            $id = $mapper::create()->execute($obj);
        } else {
            $mapper();
        }

        $data = [
            $account,
            null, $obj,
            StringUtils::intHash(\is_string($mapper) ? $mapper : \get_class($mapper)), $trigger,
            static::NAME,
            (string) $id,
            null,
            $ip,
        ];

        /** @phpstan-ignore-next-line */
        self::$auditor?->eventLogCreate(...$data);
        $this->app->eventManager->triggerSimilar('POST:Module:' . $trigger, '', $data);
    }

    /**
     * Create a model
     *
     *  1. Execute pre DB interaction event
     *  2. Create model in DB
     *  3. Execute post DB interaction event (e.g. generates an audit log)
     *
     * @param int               $account Account id
     * @param array             $objs    Response object
     * @param string | \Closure $mapper  Object mapper
     * @param string            $trigger Trigger for the event manager
     * @param string            $ip      Ip
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected function createModels(int $account, array $objs, string | \Closure $mapper, string $trigger, string $ip) : void
    {
        $trigger = static::NAME . '-' . $trigger . '-create';

        foreach ($objs as $obj) {
            $this->app->eventManager->triggerSimilar('PRE:Module:' . $trigger, '', $obj);
            $id = 0;

            if (\is_string($mapper)) {
                $id = $mapper::create()->execute($obj);
            } else {
                $mapper();
            }

            $data = [
                $account,
                null, $obj,
                StringUtils::intHash(\is_string($mapper) ? $mapper : \get_class($mapper)), $trigger,
                static::NAME,
                (string) $id,
                null,
                $ip,
            ];

            /** @phpstan-ignore-next-line */
            self::$auditor?->eventLogCreate(...$data);
            $this->app->eventManager->triggerSimilar('POST:Module:' . $trigger, '', $data);
        }
    }

    /**
     * Update a model
     *
     *  1. Execute pre DB interaction event
     *  2. Update model in DB
     *  3. Execute post DB interaction event (e.g. generates an audit log)
     *
     * @param int               $account Account id
     * @param mixed             $old     Response object old
     * @param mixed             $new     Response object new
     * @param string | \Closure $mapper  Object mapper
     * @param string            $trigger Trigger for the event manager
     * @param string            $ip      Ip
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected function updateModel(int $account, mixed $old, mixed $new, string | \Closure $mapper, string $trigger, string $ip) : void
    {
        $trigger = static::NAME . '-' . $trigger . '-update';

        $this->app->eventManager->triggerSimilar('PRE:Module:' . $trigger, '', $old);
        $id = 0;

        if (\is_string($mapper)) {
            $id = $mapper::update()->execute($new);
        } else {
            $mapper();
        }

        $data = [
            $account,
            $old, $new,
            StringUtils::intHash(\is_string($mapper) ? $mapper : \get_class($mapper)), $trigger,
            static::NAME,
            (string) $id,
            null,
            $ip,
        ];

        /** @phpstan-ignore-next-line */
        self::$auditor?->eventLogUpdate(...$data);
        $this->app->eventManager->triggerSimilar('POST:Module:' . $trigger, '', $data);
    }

    /**
     * Delete a model
     *
     *  1. Execute pre DB interaction event
     *  2. Delete model in DB
     *  3. Execute post DB interaction event (e.g. generates an audit log)
     *
     * @param int               $account Account id
     * @param mixed             $obj     Response object
     * @param string | \Closure $mapper  Object mapper
     * @param string            $trigger Trigger for the event manager
     * @param string            $ip      Ip
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected function deleteModel(int $account, mixed $obj, string | \Closure $mapper, string $trigger, string $ip) : void
    {
        $trigger = static::NAME . '-' . $trigger . '-delete';

        $this->app->eventManager->triggerSimilar('PRE:Module:' . $trigger, '', $obj);
        $id = 0;

        if (\is_string($mapper)) {
            $id = $mapper::delete()->execute($obj);
        } else {
            $mapper();
        }

        $data = [
            $account,
            $obj,  null,
            StringUtils::intHash(\is_string($mapper) ? $mapper : \get_class($mapper)), $trigger,
            static::NAME,
            (string) $id,
            null,
            $ip,
        ];

        /** @phpstan-ignore-next-line */
        self::$auditor?->eventLogDelete(...$data);
        $this->app->eventManager->triggerSimilar('POST:Module:' . $trigger, '', $data);
    }

    /**
     * Soft delete a model (only marks model as deleted)
     *
     *  1. Execute pre DB interaction event
     *  2. Set model status to deleted in DB
     *  3. Execute post DB interaction event (e.g. generates an audit log)
     *
     * @param int               $account Account id
     * @param mixed             $obj     Response object
     * @param string | \Closure $mapper  Object mapper
     * @param string            $trigger Trigger for the event manager
     * @param string            $ip      Ip
     *
     * @return void
     *
     * @feature Implement softDelete functionality.
     *          Models which have a soft delete cannot be used, read or modified unless a person has soft delete permissions
     *          In addition to DELETE permissions we now need SOFTDELETE as well.
     *          There also needs to be an undo function for this soft delete
     *          In a backend environment a soft delete would be very helpful!!!
     *
     * @since 1.0.0
     */
    protected function softDeleteModel(int $account, mixed $obj, string | \Closure $mapper, string $trigger, string $ip) : void
    {
    }

    /**
     * Create a model relation
     *
     *  1. Execute pre DB interaction event
     *  2. Create model relation in DB
     *  3. Execute post DB interaction event (e.g. generates an audit log)
     *
     * @param int    $account Account id
     * @param mixed  $rel1    Object relation1 (parent object)
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
    protected function createModelRelation(
        int $account,
        mixed $rel1,
        mixed $rel2,
        string $mapper,
        string $field,
        string $trigger,
        string $ip
    ) : void
    {
        if (empty($rel1) || empty($rel2)) {
            return;
        }

        $trigger = static::NAME . '-' . $trigger . '-relation-create';

        $this->app->eventManager->triggerSimilar('PRE:Module:' . $trigger, '', $rel1);
        $mapper::writer()->createRelationTable($field, \is_array($rel2) ? $rel2 : [$rel2], $rel1);

        $data = [
            $account,
            '', [$rel1 => $rel2],
            StringUtils::intHash($mapper), $trigger,
            static::NAME,
            null,
            null,
            $ip,
        ];

        /** @phpstan-ignore-next-line */
        self::$auditor?->eventLogRelationCreate(...$data);
        $this->app->eventManager->triggerSimilar('POST:Module:' . $trigger, '', $data);
    }

    /**
     * Delete a model relation
     *
     *  1. Execute pre DB interaction event
     *  2. Delete model in DB
     *  3. Execute post DB interaction event (e.g. generates an audit log)
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
        $mapper::remover()->deleteRelationTable($field, \is_array($rel2) ? $rel2 : [$rel2], $rel1);

        $data = [
            $account,
            [$rel1 => $rel2], '',
            StringUtils::intHash($mapper), $trigger,
            static::NAME,
            null,
            null,
            $ip,
        ];

        /** @phpstan-ignore-next-line */
        self::$auditor?->eventLogRelationDelete(...$data);
        $this->app->eventManager->triggerSimilar('POST:Module:' . $trigger, '', $data);
    }
}
