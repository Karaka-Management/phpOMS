<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package    phpOMS\Utils\Parser\Markdown
 * @copyright  Original & extra license Emanuil Rusev, erusev.com (MIT)
 * @copyright  Extended license Benjamin Hoegh (MIT)
 * @copyright  Extreme license doowzs (MIT)
 * @license    This version: OMS License 2.0
 * @version    1.0.0
 * @link       https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Parser\Markdown;

use phpOMS\Uri\UriFactory;

/**
 * Markdown parser class.
 *
 * @package    phpOMS\Utils\Parser\Markdown
 * @copyright  Original & extra license Emanuil Rusev, erusev.com (MIT)
 * @copyright  Extended license Benjamin Hoegh (MIT)
 * @copyright  Extreme license doowzs (MIT)
 * @license    This version: OMS License 2.0
 * @link       https://jingga.app
 * @see        https://github.com/erusev/parsedown
 * @see        https://github.com/erusev/parsedown-extra
 * @see        https://github.com/BenjaminHoegh/ParsedownExtended
 * @see        https://github.com/doowzs/parsedown-extreme
 * @since      1.0.0
 *
 * @todo Add special markdown content
 *  1. Calendar (own widget)
 *  2. Event (own widget)
 *  3. Tasks (own widget)
 *  4. Vote/Survey (own widget)
 *  5. Website link/embed widgets (facebook, linkedIn, twitter, ...)
 *  6. User/Supplier/Client/Employee (own widget, should make use of schema)
 *  7. Address (own widget, should make use of schema)
 *  8. Contact (own widget, should make use of schema)
 *  9. Item (own widget, should make use of schema)
 * 10. Progress radial
 * 11. Timeline horizontal/vertical/matrix
 * 12. Tabs horizontal/vertical
 * 13. Checklist (own widget)
 * 14. Gallery
 * 15. Form (own widget)
 * https://github.com/Karaka-Management/phpOMS/issues/290
 */
class Markdown
{
    /**
     * Parsedown version
     *
     * @var string
     * @since 1.0.0
     */
    public const VERSION = '2.0.0';

    /**
     * Special markdown characters
     *
     * @var string[]
     * @since 1.0.0
     */
    protected array $specialCharacters = [
        '\\', '`', '*', '_', '{', '}', '[', ']', '(', ')', '>', '#', '+', '-', '.', '!', '|', '?', '"', "'", '<',
    ];

    /**
     * Regexes for html strong
     *
     * @var array<string, string>
     * @since 1.0.0
     */
    protected array $strongRegex = [
        '*' => '/^[*]{2}((?:\\\\\*|[^*]|[*][^*]*+[*])+?)[*]{2}(?![*])/s',
    ];

    /**
     * Regexes for html underline
     *
     * @var array<string, string>
     * @since 1.0.0
     */
    protected array $underlineRegex = [
        '_' => '/^[_]{2}((?:\\\\\_|[^_]|[_][^_]_+[_])+?)[_]{2}(?![_])/s',
    ];

    /**
     * Regexes for html emphasizes
     *
     * @var array<string, string>
     * @since 1.0.0
     */
    protected array $emRegex = [
        '*' => '/^[*]((?:\\\\\*|[^*]|[*][*][^*]+?[*][*])+?)[*](?![*])/s',
        '_' => '/^_((?:\\\\_|[^_]|__[^_]*__)+?)_(?!_)\b/us',
    ];

    /**
     * Regex for html attributes
     *
     * @var string
     * @since 1.0.0
     */
    protected string $regexHtmlAttribute = '[a-zA-Z_:][\w:.-]*+(?:\s*+=\s*+(?:[^"\'=<>`\s]+|"[^"]*+"|\'[^\']*+\'))?+';

    /**
     * Regex for for classes and ids
     *
     * @var string
     * @since 1.0.0
     */
    protected string $regexAttribute = '(?:[#.][-\w]+[ ]*)';

    /**
     * Elements without closing
     *
     * @var string[]
     * @since 1.0.0
     */
    protected array $voidElements = [
        'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source',
    ];

    /**
     * Text elements
     *
     * @var string[]
     * @since 1.0.0
     */
    protected array $textLevelElements = [
        'a', 'br', 'bdo', 'abbr', 'blink', 'nextid', 'acronym', 'basefont',
        'b', 'em', 'big', 'cite', 'small', 'spacer', 'listing',
        'i', 'rp', 'del', 'code',          'strike', 'marquee',
        'q', 'rt', 'ins', 'font',          'strong',
        's', 'tt', 'kbd', 'mark',
        'u', 'xm', 'sub', 'nobr',
                   'sup', 'ruby',
                   'var', 'span',
                   'wbr', 'time',
    ];

    /**
     * Inline special characters (not block elements)
     *
     * @var array<string, string[]>
     * @since 1.0.0
     */
    protected array $inlineTypes = [
        '!'  => ['Image'],
        '*'  => ['Emphasis'],
        '_'  => ['Emphasis'],
        '&'  => ['SpecialCharacter'],
        '['  => ['FootnoteMarker', 'Link'],
        ':'  => ['Url'],
        '<'  => ['UrlTag', 'EmailTag', 'Markup'],
        '`'  => ['Code'],
        '~'  => ['Strikethrough'],
        '\\' => ['EscapeSequence'],
    ];

    /**
     * Inline special characters (not block elements) (see $inlineTypes)
     *
     * @var string
     * @since 1.0.0
     */
    protected string $inlineMarkerList = '!*_&[:<`~';

    /**
     * Uses strict mode?
     *
     * Less forgiving with regards to formatting.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $strictMode = false;

    /**
     * Uses safe mode (true -> no html allowed)?
     *
     * Important for parsing or sanitizing html.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $safeMode = false;

    /**
     * Urls are always handled as links
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $urlsLinked = true;

    /**
     * Should html get escaped?
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $markupEscaped = false;

    /**
     * Replaces new lines with <br> in inline elements
     *
     * true  -> replaces new line with br
     * false -> replaces double whitespace followed with new line with br
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $breaksEnabled = false;

    /**
     * Save link prefixes
     *
     * @var string[]
     * @since 1.0.0
     */
    protected array $safeLinksWhitelist = [
        'http://',
        'https://',
        'ftp://',
        'ftps://',
        'mailto:',
        'tel:',
        'data:image/png;base64,',
        'data:image/gif;base64,',
        'data:image/jpeg;base64,',
        'irc:',
        'ircs:',
        'git:',
        'ssh:',
        'news:',
        'steam:',
    ];

    /**
     * Block special characters
     *
     * @var array<int|string, string[]>
     * @since 1.0.0
     */
    protected array $blockTypes = [
        '#' => ['Header'],
        '*' => ['Rule', 'List', 'Abbreviation'],
        '+' => ['List'],
        '-' => ['SetextHeader', 'Table', 'Rule', 'List'],
        '0' => ['List'],
        '1' => ['List'],
        '2' => ['List'],
        '3' => ['List'],
        '4' => ['List'],
        '5' => ['List'],
        '6' => ['List'],
        '7' => ['List'],
        '8' => ['List'],
        '9' => ['List'],
        ':' => ['Table', 'DefinitionList'],
        '<' => ['Comment', 'Markup'],
        '=' => ['SetextHeader'],
        '>' => ['Quote'],
        '[' => ['Footnote', 'Reference'],
        '_' => ['Rule'],
        '`' => ['FencedCode'],
        '|' => ['Table'],
    ];

    /**
     * Unmarked block types
     *
     * @var string[]
     * @since 1.0.0
     */
    protected array $unmarkedBlockTypes = [
        'Code',
    ];

    /**
     * Is continuable
     *
     * @var string[]
     * @since 1.0.0
     */
    private const CONTINUABLE = [
        'Code', 'Comment', 'FencedCode', 'List', 'Quote', 'Table', 'Math', 'Spoiler', 'Checkbox', 'Footnote', 'DefinitionList', 'Markup',
    ];

    /**
     * Is completable
     *
     * @var string[]
     * @since 1.0.0
     */
    private const COMPLETABLE = [
        'Math', 'Spoiler', 'Table', 'Checkbox', 'Footnote', 'Markup', 'Code', 'FencedCode', 'List',
    ];

    /**
     * Parsing options
     *
     * @var array
     * @since 1.0.0
     */
    private array $options = [];

    /**
     * Definition data
     *
     * E.g. abbreviations, footnotes
     *
     * @var array
     * @since 1.0.0
     */
    protected array $definitionData = [];

    // TOC: start
    /**
     * Table of content id
     *
     * @var string
     * @since 1.0.0
     */
    public string $idToc = 'toc';

    /**
     * TOC array after parsing headers
     *
     * @var array{}|array{text:string, id:string, level:string}
     * @since 1.0.0
     */
    protected $contentsListArray = [];

    /**
     * TOC string after parsing headers
     *
     * @var string
     * @since 1.0.0
     */
    protected $contentsListString = '';

    /**
     * First head level
     *
     * @var int
     * @since 1.0.0
     */
    protected int $firstHeadLevel = 0;

    /**
     * Is header blacklist (for table of contents/TOC) initialized
     *
     * @var bool
     * @since 1.0.0
     */
    protected $isBlacklistInitialized = false;

    /**
     * Header duplicates (same header text)
     *
     * @var array<string, int>
     * @since 1.0.0
     */
    protected $anchorDuplicates = [];
    // TOC: end

    /**
     * Footnote count
     *
     * @var int
     * @since 1.0.0
     */
    private int $footnoteCount = 0;

    /**
     * Current abbreviation
     *
     * @var string
     * @since 1.0.0
     */
    private string $currentAbreviation;

    /**
     * Current abbreviation meaning
     *
     * @var string
     * @since 1.0.0
     */
    private string $currentMeaning;

    /**
     * Instances
     *
     * @var array<string, self>
     * @since 1.0.0
     */
    private static $instances = [];

    /**
     * Clean up state
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function clean() : void
    {
        $this->definitionData     = [];
        $this->contentsListArray  = [];
        $this->contentsListString = '';
        $this->firstHeadLevel     = 0;
        $this->anchorDuplicates   = [];
        $this->footnoteCount      = 0;
        $this->currentAbreviation = '';
        $this->currentMeaning     = '';
    }

    /**
     * Create instance for static use
     *
     * @param string $name Instance name
     *
     * @return self
     *
     * @since 1.0.0
     */
    public static function getInstance(string $name = 'default') : self
    {
        if (isset(self::$instances[$name])) {
            $obj = self::$instances[$name];
            $obj->clean();

            return self::$instances[$name];
        }

        $instance = new self();

        self::$instances[$name] = $instance;

        return $instance;
    }

    /**
     * Constructor.
     *
     * @param array $params Parameters
     *
     * @since 1.0.0
     */
    public function __construct(array $params = [])
    {
        $this->options        = $params;
        $this->options['toc'] = $this->options['toc'] ?? false;

        // Marks
        if ($this->options['mark'] ?? true) {
            $this->inlineTypes['='][] = 'Mark';
            $this->inlineMarkerList .= '=';
        }

        // Keystrokes
        if ($this->options['keystrokes'] ?? true) {
            $this->inlineTypes['['][] = 'Keystrokes';
        }

        // Spoiler
        if ($this->options['spoiler'] ?? false) {
            $this->inlineTypes['>'][] = 'Spoiler';
            $this->inlineMarkerList .= '>';
        }

        // Inline Math
        if ($this->options['math'] ?? false) {
            $this->inlineTypes['\\'][] = 'Math';
            $this->inlineTypes['$'][]  = 'Math';
            $this->inlineMarkerList .= '$';
        }

        // Superscript
        if ($this->options['sup'] ?? false) {
            $this->inlineTypes['^'][] = 'Superscript';
            $this->inlineMarkerList .= '^';
        }

        // Subscript
        if ($this->options['sub'] ?? false) {
            $this->inlineTypes['~'][] = 'Subscript';
        }

        // Emojis
        if ($this->options['emojis'] ?? true) {
            $this->inlineTypes[':'][] = 'Emojis';
        }

        // Typographer
        if ($this->options['typographer'] ?? false) {
            $this->inlineTypes['('][] = 'Typographer';
            $this->inlineMarkerList .= '(';
            $this->inlineTypes['.'][] = 'Typographer';
            $this->inlineMarkerList .= '.';
            $this->inlineTypes['+'][] = 'Typographer';
            $this->inlineMarkerList .= '+';
            $this->inlineTypes['!'][] = 'Typographer';
            $this->inlineTypes['?'][] = 'Typographer';
            $this->inlineMarkerList .= '?';
        }

        // Block Math
        if ($this->options['math'] ?? false) {
            $this->blockTypes['\\'][] = 'Math';
            $this->blockTypes['$'][]  = 'Math';
        }

        // Block Spoiler
        if ($this->options['spoiler'] ?? false) {
            $this->blockTypes['?'][] = 'Spoiler';
        }

        // Checkbox
        if ($this->options['lists']['checkbox'] ?? true) {
            $this->blockTypes['['][] = 'Checkbox';
        }

        // Embedding
        if ($this->options['embedding'] ?? false) {
            $this->inlineTypes['['][] = 'Embedding';
        }

        // Map
        if ($this->options['map'] ?? false) {
            $this->inlineTypes['['][] = 'Map';
        }

        // Address
        if ($this->options['address'] ?? false) {
            $this->inlineTypes['['][] = 'Address';
        }

        // Contact
        if ($this->options['contact'] ?? false) {
            $this->inlineTypes['['][] = 'Contact';
        }

        // Progress
        if ($this->options['progress'] ?? false) {
            $this->inlineTypes['['][] = 'Progress';
        }

        // Escaping needs to happen at the end
        $this->inlineMarkerList .= '\\';
    }

    /**
     * Parses the given markdown string to a HTML
     *
     * @param string $text Markdown text to parse
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function parse(string $text) : string
    {
        $parsedown = self::getInstance();

        return $parsedown->text($text);
    }

    /**
     * Parses the given markdown string to a HTML string but it ignores ToC
     *
     * @param string $text Markdown text to parse
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function body(string $text) : string
    {
        $text = $this->encodeToCTagToHash($text);  // Escapes ToC tag temporary

        $elements = $this->textElements($text);
        $html     = $this->elements($elements);
        $html     = \trim($html, "\n");

        // Merge consecutive dl elements
        $html = \preg_replace('/<\/dl>\s+<dl>\s+/', '', $html) ?? '';

        // Add footnotes
        if (isset($this->definitionData['Footnote'])) {
            $element = $this->buildFootnoteElement();
            $html .= "\n" . $this->element($element);
        }

        return $this->decodeToCTagFromHash($html); // Unescape the ToC tag
    }

    /**
     * Parses markdown string to HTML and also the "[toc]" tag as well.
     *
     * @param string $text Markdown text to parse
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function text(string $text) : string
    {
        // Parses the markdown text except the ToC tag. This also searches
        // the list of contents and available to get from "contentsList()"
        // method.
        $html = $this->body($text);

        if (isset($this->options['toc']) && $this->options['toc'] === false) {
            return $html;
        }

        // Handle toc
        $tagOrigin = $this->options['toc']['set_toc_tag'] ?? '[toc]';

        if (\strpos($text, $tagOrigin) === false) {
            return $html;
        }

        $tocData = $this->contentsList();
        $needle  = '<p>' . $tagOrigin . '</p>';
        $replace = '<div id="' . $this->idToc . '">' . $tocData . '</div>';

        return \str_replace($needle, $replace, $html);
    }

    /**
     * Returns the parsed ToC.
     *
     * @param string $typeReturn Type of the return format. "html" or "json".
     *
     * @return string HTML/JSON string of ToC
     *
     * @since 1.0.0
     */
    public function contentsList($typeReturn = 'html') : string
    {
        if (\strtolower($typeReturn) === 'json') {
            $json = \json_encode($this->contentsListArray);

            return $json === false ? '' : $json;
        }

        $result = '';
        if (!empty($this->contentsListString)) {
            // Parses the ToC list in markdown to HTML
            $result = $this->body($this->contentsListString);
        }

        return $result;
    }

    /**
     * Handle inline code
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineCode(array $excerpt) : ?array
    {
        if (!($this->options['code']['inline'] ?? true)
            || !($this->options['code'] ?? true)
        ) {
            return null;
        }

        $marker = $excerpt['text'][0];

        if (\preg_match(
                '/^([' . $marker . ']++)[ ]*+(.+?)[ ]*+(?<![' . $marker . '])\1(?!' . $marker . ')/s',
                $excerpt['text'], $matches
            ) !== 1
        ) {
            return null;
        }

        $text = $matches[2];
        $text = \preg_replace('/[ ]*+\n/', ' ', $text);

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'name' => 'code',
                'text' => $text,
            ],
        ];
    }

    /**
     * Handle inline email
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineEmailTag(array $excerpt) : ?array
    {
        if (!($this->options['links'] ?? true)
            || !($this->options['links']['email_links'] ?? true)
        ) {
            return null;
        }

        $hostnameLabel   = '[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?';
        $commonMarkEmail = '[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]++@' . $hostnameLabel . '(?:\.' . $hostnameLabel . ')*';

        if (\strpos($excerpt['text'], '>') === false
            || \preg_match('/^<((mailto:)?' . $commonMarkEmail . ')>/i', $excerpt['text'], $matches) !== 1
        ) {
            return null;
        }

        $url = UriFactory::build($matches[1]);

        if (!isset($matches[2])) {
            $url = "mailto:{$url}";
        }

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'name'       => 'a',
                'text'       => $matches[1],
                'attributes' => [
                    'href' => $url,
                ],
            ],
        ];
    }

    /**
     * Inline emphasis
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineEmphasis(array $excerpt) : ?array
    {
        if (!($this->options['emphasis'] ?? true)
            || !isset($excerpt['text'][1])
        ) {
            return null;
        }

        $marker = $excerpt['text'][0];

        if ($excerpt['text'][1] === $marker
            && isset($this->strongRegex[$marker]) && \preg_match($this->strongRegex[$marker], $excerpt['text'], $matches)
        ) {
            $emphasis = 'strong';
        } elseif ($excerpt['text'][1] === $marker
            && isset($this->underlineRegex[$marker]) && \preg_match($this->underlineRegex[$marker], $excerpt['text'], $matches)
        ) {
            $emphasis = 'u';
        } elseif (\preg_match($this->emRegex[$marker], $excerpt['text'], $matches)) {
            $emphasis = 'em';
        } else {
            return null;
        }

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'name'    => $emphasis,
                'handler' => [
                    'function'    => 'lineElements',
                    'argument'    => $matches[1],
                    'destination' => 'elements',
                ],
            ],
        ];
    }

    /**
     * Handle image
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineImage(array $excerpt) : ?array
    {
        if (!($this->options['images'] ?? true)
            || !\str_starts_with($excerpt['text'], '![')
        ) {
            return null;
        }

        $excerpt['text'] = \substr($excerpt['text'], 1);
        $link            = $this->inlineLink($excerpt);

        if ($link === null) {
            return null;
        }

        $inline = [
            'extent'  => $link['extent'] + 1,
            'element' => [
                'name'       => 'img',
                'attributes' => [
                    'src' => $link['element']['attributes']['href'],
                    'alt' => $link['element']['handler']['argument'],
                ],
                'autobreak' => true,
            ],
        ];

        $inline['element']['attributes'] += $link['element']['attributes'];

        unset($inline['element']['attributes']['href']);

        return $inline;
    }

    /**
     * Handle link
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function inlineLink(array $excerpt) : ?array
    {
        if (!($this->options['links'] ?? true)) {
            return null;
        }

        $link      = $this->inlineLinkParent($excerpt);
        $remainder = $link !== null ? \substr($excerpt['text'], $link['extent']) : '';

        if (\preg_match('/^[ ]*{(' . $this->regexAttribute . '+)}/', $remainder, $matches)) {
            $link['extent']                += \strlen($matches[0]);
            $link['element']['attributes'] += $this->parseAttributeData($matches[1]);
        }

        return $link;
    }

    /**
     * Handle markup
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineMarkup(array $excerpt) : ?array
    {
        if (!($this->options['markup'] ?? true)
            || $this->markupEscaped || $this->safeMode || \strpos($excerpt['text'], '>') === false
        ) {
            return null;
        }

        $matches = [];
        if (($excerpt['text'][1] === '/' && \preg_match('/^<\/\w[\w-]*+[ ]*+>/s', $excerpt['text'], $matches))
            || ($excerpt['text'][1] === '!' && \preg_match('/^<!---?[^>-](?:-?+[^-])*-->/s', $excerpt['text'], $matches))
            || ($excerpt['text'][1] !== ' ' && \preg_match('/^<\w[\w-]*+(?:[ ]*+' . $this->regexHtmlAttribute . ')*+[ ]*+\/?>/s', $excerpt['text'], $matches))
        ) {
            return [
                'extent'  => \strlen($matches[0]),
                'element' => ['rawHtml' => $matches[0]],
            ];
        }

        return null;
    }

    /**
     * Handle strikethrough
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineStrikethrough(array $excerpt) : ?array
    {
        if (!($this->options['strikethroughs'] ?? true)
            || !\str_starts_with($excerpt['text'], '~~')
            || \preg_match('/^~~(?=\S)(.+?)(?<=\S)~~/', $excerpt['text'], $matches) !== 1
        ) {
            return null;
        }

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'name'    => 'del',
                'handler' => [
                    'function'    => 'lineElements',
                    'argument'    => $matches[1],
                    'destination' => 'elements',
                ],
            ],
        ];
    }

    /**
     * Handle url
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, position:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineUrl(array $excerpt) : ?array
    {
        if (!($this->options['links'] ?? true)
            || !$this->urlsLinked || !\str_starts_with($excerpt['text'], '://')
            || \strpos($excerpt['context'], 'http') === false
            || \preg_match('/\bhttps?+:[\/]{2}[^\s<]+\b\/*+/ui', $excerpt['context'], $matches, \PREG_OFFSET_CAPTURE) !== 1
        ) {
            return null;
        }

        $url = UriFactory::build($matches[0][0]);

        return [
            'extent'   => \strlen($matches[0][0]),
            'position' => $matches[0][1],
            'element'  => [
                'name'       => 'a',
                'text'       => $url,
                'attributes' => [
                    'href' => $url,
                ],
            ],
        ];
    }

    /**
     * Handle url
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineUrlTag(array $excerpt) : ?array
    {
        if (!($this->options['links'] ?? true)
            || \strpos($excerpt['text'], '>') === false
            || \preg_match('/^<(\w++:\/{2}[^ >]++)>/i', $excerpt['text'], $matches) !== 1
        ) {
            return null;
        }

        $url = UriFactory::build($matches[1]);

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'name'       => 'a',
                'text'       => $url,
                'attributes' => [
                    'href' => $url,
                ],
            ],
        ];
    }

    /**
     * Handle emojis
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineEmojis(array $excerpt) : ?array
    {
        if (\preg_match('/^(:)([^: ]*?)(:)/', $excerpt['text'], $matches) !== 1) {
            return null;
        }

        $emojiMap = [
            ':smile:'                           => '😄', ':laughing:' => '😆', ':blush:' => '😊', ':smiley:' => '😃',
            ':relaxed:'                         => '☺️', ':smirk:' => '😏', ':heart_eyes:' => '😍', ':kissing_heart:' => '😘',
            ':kissing_closed_eyes:'             => '😚', ':flushed:' => '😳', ':relieved:' => '😌', ':satisfied:' => '😆',
            ':grin:'                            => '😁', ':wink:' => '😉', ':stuck_out_tongue_winking_eye:' => '😜', ':stuck_out_tongue_closed_eyes:' => '😝',
            ':grinning:'                        => '😀', ':kissing:' => '😗', ':kissing_smiling_eyes:' => '😙', ':stuck_out_tongue:' => '😛',
            ':sleeping:'                        => '😴', ':worried:' => '😟', ':frowning:' => '😦', ':anguished:' => '😧',
            ':open_mouth:'                      => '😮', ':grimacing:' => '😬', ':confused:' => '😕', ':hushed:' => '😯',
            ':expressionless:'                  => '😑', ':unamused:' => '😒', ':sweat_smile:' => '😅', ':sweat:' => '😓',
            ':disappointed_relieved:'           => '😥', ':weary:' => '😩', ':pensive:' => '😔', ':disappointed:' => '😞',
            ':confounded:'                      => '😖', ':fearful:' => '😨', ':cold_sweat:' => '😰', ':persevere:' => '😣',
            ':cry:'                             => '😢', ':sob:' => '😭', ':joy:' => '😂', ':astonished:' => '😲',
            ':scream:'                          => '😱', ':tired_face:' => '😫', ':angry:' => '😠', ':rage:' => '😡',
            ':triumph:'                         => '😤', ':sleepy:' => '😪', ':yum:' => '😋', ':mask:' => '😷',
            ':sunglasses:'                      => '😎', ':dizzy_face:' => '😵', ':imp:' => '👿', ':smiling_imp:' => '😈',
            ':neutral_face:'                    => '😐', ':no_mouth:' => '😶', ':innocent:' => '😇', ':alien:' => '👽',
            ':yellow_heart:'                    => '💛', ':blue_heart:' => '💙', ':purple_heart:' => '💜', ':heart:' => '❤️',
            ':green_heart:'                     => '💚', ':broken_heart:' => '💔', ':heartbeat:' => '💓', ':heartpulse:' => '💗',
            ':two_hearts:'                      => '💕', ':revolving_hearts:' => '💞', ':cupid:' => '💘', ':sparkling_heart:' => '💖',
            ':sparkles:'                        => '✨', ':star:' => '⭐️', ':star2:' => '🌟', ':dizzy:' => '💫',
            ':boom:'                            => '💥', ':collision:' => '💥', ':anger:' => '💢', ':exclamation:' => '❗️',
            ':question:'                        => '❓', ':grey_exclamation:' => '❕', ':grey_question:' => '❔', ':zzz:' => '💤',
            ':dash:'                            => '💨', ':sweat_drops:' => '💦', ':notes:' => '🎶', ':musical_note:' => '🎵',
            ':fire:'                            => '🔥', ':hankey:' => '💩', ':poop:' => '💩', ':shit:' => '💩',
            ':+1:'                              => '👍', ':thumbsup:' => '👍', ':-1:' => '👎', ':thumbsdown:' => '👎',
            ':ok_hand:'                         => '👌', ':punch:' => '👊', ':facepunch:' => '👊', ':fist:' => '✊',
            ':v:'                               => '✌️', ':wave:' => '👋', ':hand:' => '✋', ':raised_hand:' => '✋',
            ':open_hands:'                      => '👐', ':point_up:' => '☝️', ':point_down:' => '👇', ':point_left:' => '👈',
            ':point_right:'                     => '👉', ':raised_hands:' => '🙌', ':pray:' => '🙏', ':point_up_2:' => '👆',
            ':clap:'                            => '👏', ':muscle:' => '💪', ':metal:' => '🤘', ':fu:' => '🖕',
            ':walking:'                         => '🚶', ':runner:' => '🏃', ':running:' => '🏃', ':couple:' => '👫',
            ':family:'                          => '👪', ':two_men_holding_hands:' => '👬', ':two_women_holding_hands:' => '👭', ':dancer:' => '💃',
            ':dancers:'                         => '👯', ':ok_woman:' => '🙆', ':no_good:' => '🙅', ':information_desk_person:' => '💁',
            ':raising_hand:'                    => '🙋', ':bride_with_veil:' => '👰', ':person_with_pouting_face:' => '🙎', ':person_frowning:' => '🙍',
            ':bow:'                             => '🙇', ':couple_with_heart:' => '💑', ':massage:' => '💆', ':haircut:' => '💇',
            ':nail_care:'                       => '💅', ':boy:' => '👦', ':girl:' => '👧', ':woman:' => '👩',
            ':man:'                             => '👨', ':baby:' => '👶', ':older_woman:' => '👵', ':older_man:' => '👴',
            ':person_with_blond_hair:'          => '👱', ':man_with_gua_pi_mao:' => '👲', ':man_with_turban:' => '👳', ':construction_worker:' => '👷',
            ':cop:'                             => '👮', ':angel:' => '👼', ':princess:' => '👸', ':smiley_cat:' => '😺',
            ':smile_cat:'                       => '😸', ':heart_eyes_cat:' => '😻', ':kissing_cat:' => '😽', ':smirk_cat:' => '😼',
            ':scream_cat:'                      => '🙀', ':crying_cat_face:' => '😿', ':joy_cat:' => '😹', ':pouting_cat:' => '😾',
            ':japanese_ogre:'                   => '👹', ':japanese_goblin:' => '👺', ':see_no_evil:' => '🙈', ':hear_no_evil:' => '🙉',
            ':speak_no_evil:'                   => '🙊', ':guardsman:' => '💂', ':skull:' => '💀', ':feet:' => '🐾',
            ':lips:'                            => '👄', ':kiss:' => '💋', ':droplet:' => '💧', ':ear:' => '👂',
            ':eyes:'                            => '👀', ':nose:' => '👃', ':tongue:' => '👅', ':love_letter:' => '💌',
            ':bust_in_silhouette:'              => '👤', ':busts_in_silhouette:' => '👥', ':speech_balloon:' => '💬', ':thought_balloon:' => '💭',
            ':sunny:'                           => '☀️', ':umbrella:' => '☔️', ':cloud:' => '☁️', ':snowflake:' => '❄️',
            ':snowman:'                         => '⛄️', ':zap:' => '⚡️', ':cyclone:' => '🌀', ':foggy:' => '🌁',
            ':ocean:'                           => '🌊', ':cat:' => '🐱', ':dog:' => '🐶', ':mouse:' => '🐭',
            ':hamster:'                         => '🐹', ':rabbit:' => '🐰', ':wolf:' => '🐺', ':frog:' => '🐸',
            ':tiger:'                           => '🐯', ':koala:' => '🐨', ':bear:' => '🐻', ':pig:' => '🐷',
            ':pig_nose:'                        => '🐽', ':cow:' => '🐮', ':boar:' => '🐗', ':monkey_face:' => '🐵',
            ':monkey:'                          => '🐒', ':horse:' => '🐴', ':racehorse:' => '🐎', ':camel:' => '🐫',
            ':sheep:'                           => '🐑', ':elephant:' => '🐘', ':panda_face:' => '🐼', ':snake:' => '🐍',
            ':bird:'                            => '🐦', ':baby_chick:' => '🐤', ':hatched_chick:' => '🐥', ':hatching_chick:' => '🐣',
            ':chicken:'                         => '🐔', ':penguin:' => '🐧', ':turtle:' => '🐢', ':bug:' => '🐛',
            ':honeybee:'                        => '🐝', ':ant:' => '🐜', ':beetle:' => '🐞', ':snail:' => '🐌',
            ':octopus:'                         => '🐙', ':tropical_fish:' => '🐠', ':fish:' => '🐟', ':whale:' => '🐳',
            ':whale2:'                          => '🐋', ':dolphin:' => '🐬', ':cow2:' => '🐄', ':ram:' => '🐏',
            ':rat:'                             => '🐀', ':water_buffalo:' => '🐃', ':tiger2:' => '🐅', ':rabbit2:' => '🐇',
            ':dragon:'                          => '🐉', ':goat:' => '🐐', ':rooster:' => '🐓', ':dog2:' => '🐕',
            ':pig2:'                            => '🐖', ':mouse2:' => '🐁', ':ox:' => '🐂', ':dragon_face:' => '🐲',
            ':blowfish:'                        => '🐡', ':crocodile:' => '🐊', ':dromedary_camel:' => '🐪', ':leopard:' => '🐆',
            ':cat2:'                            => '🐈', ':poodle:' => '🐩', ':crab' => '🦀', ':paw_prints:' => '🐾', ':bouquet:' => '💐',
            ':cherry_blossom:'                  => '🌸', ':tulip:' => '🌷', ':four_leaf_clover:' => '🍀', ':rose:' => '🌹',
            ':sunflower:'                       => '🌻', ':hibiscus:' => '🌺', ':maple_leaf:' => '🍁', ':leaves:' => '🍃',
            ':fallen_leaf:'                     => '🍂', ':herb:' => '🌿', ':mushroom:' => '🍄', ':cactus:' => '🌵',
            ':palm_tree:'                       => '🌴', ':evergreen_tree:' => '🌲', ':deciduous_tree:' => '🌳', ':chestnut:' => '🌰',
            ':seedling:'                        => '🌱', ':blossom:' => '🌼', ':ear_of_rice:' => '🌾', ':shell:' => '🐚',
            ':globe_with_meridians:'            => '🌐', ':sun_with_face:' => '🌞', ':full_moon_with_face:' => '🌝', ':new_moon_with_face:' => '🌚',
            ':new_moon:'                        => '🌑', ':waxing_crescent_moon:' => '🌒', ':first_quarter_moon:' => '🌓', ':waxing_gibbous_moon:' => '🌔',
            ':full_moon:'                       => '🌕', ':waning_gibbous_moon:' => '🌖', ':last_quarter_moon:' => '🌗', ':waning_crescent_moon:' => '🌘',
            ':last_quarter_moon_with_face:'     => '🌜', ':first_quarter_moon_with_face:' => '🌛', ':moon:' => '🌔', ':earth_africa:' => '🌍',
            ':earth_americas:'                  => '🌎', ':earth_asia:' => '🌏', ':volcano:' => '🌋', ':milky_way:' => '🌌',
            ':partly_sunny:'                    => '⛅️', ':bamboo:' => '🎍', ':gift_heart:' => '💝', ':dolls:' => '🎎',
            ':school_satchel:'                  => '🎒', ':mortar_board:' => '🎓', ':flags:' => '🎏', ':fireworks:' => '🎆',
            ':sparkler:'                        => '🎇', ':wind_chime:' => '🎐', ':rice_scene:' => '🎑', ':jack_o_lantern:' => '🎃',
            ':ghost:'                           => '👻', ':santa:' => '🎅', ':christmas_tree:' => '🎄', ':gift:' => '🎁',
            ':bell:'                            => '🔔', ':no_bell:' => '🔕', ':tanabata_tree:' => '🎋', ':tada:' => '🎉',
            ':confetti_ball:'                   => '🎊', ':balloon:' => '🎈', ':crystal_ball:' => '🔮', ':cd:' => '💿',
            ':dvd:'                             => '📀', ':floppy_disk:' => '💾', ':camera:' => '📷', ':video_camera:' => '📹',
            ':movie_camera:'                    => '🎥', ':computer:' => '💻', ':tv:' => '📺', ':iphone:' => '📱',
            ':phone:'                           => '☎️', ':telephone:' => '☎️', ':telephone_receiver:' => '📞', ':pager:' => '📟',
            ':fax:'                             => '📠', ':minidisc:' => '💽', ':vhs:' => '📼', ':sound:' => '🔉',
            ':speaker:'                         => '🔈', ':mute:' => '🔇', ':loudspeaker:' => '📢', ':mega:' => '📣',
            ':hourglass:'                       => '⌛️', ':hourglass_flowing_sand:' => '⏳', ':alarm_clock:' => '⏰', ':watch:' => '⌚️',
            ':radio:'                           => '📻', ':satellite:' => '📡', ':loop:' => '➿', ':mag:' => '🔍',
            ':mag_right:'                       => '🔎', ':unlock:' => '🔓', ':lock:' => '🔒', ':lock_with_ink_pen:' => '🔏',
            ':closed_lock_with_key:'            => '🔐', ':key:' => '🔑', ':bulb:' => '💡', ':flashlight:' => '🔦',
            ':high_brightness:'                 => '🔆', ':low_brightness:' => '🔅', ':electric_plug:' => '🔌', ':battery:' => '🔋',
            ':calling:'                         => '📲', ':email:' => '✉️', ':mailbox:' => '📫', ':postbox:' => '📮',
            ':bath:'                            => '🛀', ':bathtub:' => '🛁', ':shower:' => '🚿', ':toilet:' => '🚽',
            ':wrench:'                          => '🔧', ':nut_and_bolt:' => '🔩', ':hammer:' => '🔨', ':seat:' => '💺',
            ':moneybag:'                        => '💰', ':yen:' => '💴', ':dollar:' => '💵', ':pound:' => '💷',
            ':euro:'                            => '💶', ':credit_card:' => '💳', ':money_with_wings:' => '💸', ':e-mail:' => '📧',
            ':inbox_tray:'                      => '📥', ':outbox_tray:' => '📤', ':envelope:' => '✉️', ':incoming_envelope:' => '📨',
            ':postal_horn:'                     => '📯', ':mailbox_closed:' => '📪', ':mailbox_with_mail:' => '📬', ':mailbox_with_no_mail:' => '📭',
            ':door:'                            => '🚪', ':smoking:' => '🚬', ':bomb:' => '💣', ':gun:' => '🔫',
            ':hocho:'                           => '🔪', ':pill:' => '💊', ':syringe:' => '💉', ':page_facing_up:' => '📄',
            ':page_with_curl:'                  => '📃', ':bookmark_tabs:' => '📑', ':bar_chart:' => '📊', ':chart_with_upwards_trend:' => '📈',
            ':chart_with_downwards_trend:'      => '📉', ':scroll:' => '📜', ':clipboard:' => '📋', ':calendar:' => '📆',
            ':date:'                            => '📅', ':card_index:' => '📇', ':file_folder:' => '📁', ':open_file_folder:' => '📂',
            ':scissors:'                        => '✂️', ':pushpin:' => '📌', ':paperclip:' => '📎', ':black_nib:' => '✒️',
            ':pencil2:'                         => '✏️', ':straight_ruler:' => '📏', ':triangular_ruler:' => '📐', ':closed_book:' => '📕',
            ':green_book:'                      => '📗', ':blue_book:' => '📘', ':orange_book:' => '📙', ':notebook:' => '📓',
            ':notebook_with_decorative_cover:'  => '📔', ':ledger:' => '📒', ':books:' => '📚', ':bookmark:' => '🔖',
            ':name_badge:'                      => '📛', ':microscope:' => '🔬', ':telescope:' => '🔭', ':newspaper:' => '📰',
            ':football:'                        => '🏈', ':basketball:' => '🏀', ':soccer:' => '⚽️', ':baseball:' => '⚾️',
            ':tennis:'                          => '🎾', ':8ball:' => '🎱', ':rugby_football:' => '🏉', ':bowling:' => '🎳',
            ':golf:'                            => '⛳️', ':mountain_bicyclist:' => '🚵', ':bicyclist:' => '🚴', ':horse_racing:' => '🏇',
            ':snowboarder:'                     => '🏂', ':swimmer:' => '🏊', ':surfer:' => '🏄', ':ski:' => '🎿',
            ':spades:'                          => '♠️', ':hearts:' => '♥️', ':clubs:' => '♣️', ':diamonds:' => '♦️',
            ':gem:'                             => '💎', ':ring:' => '💍', ':trophy:' => '🏆', ':musical_score:' => '🎼',
            ':musical_keyboard:'                => '🎹', ':violin:' => '🎻', ':space_invader:' => '👾', ':video_game:' => '🎮',
            ':black_joker:'                     => '🃏', ':flower_playing_cards:' => '🎴', ':game_die:' => '🎲', ':dart:' => '🎯',
            ':mahjong:'                         => '🀄️', ':clapper:' => '🎬', ':memo:' => '📝', ':pencil:' => '📝',
            ':book:'                            => '📖', ':art:' => '🎨', ':microphone:' => '🎤', ':headphones:' => '🎧',
            ':trumpet:'                         => '🎺', ':saxophone:' => '🎷', ':guitar:' => '🎸', ':shoe:' => '👞',
            ':sandal:'                          => '👡', ':high_heel:' => '👠', ':lipstick:' => '💄', ':boot:' => '👢',
            ':shirt:'                           => '👕', ':tshirt:' => '👕', ':necktie:' => '👔', ':womans_clothes:' => '👚',
            ':dress:'                           => '👗', ':running_shirt_with_sash:' => '🎽', ':jeans:' => '👖', ':kimono:' => '👘',
            ':bikini:'                          => '👙', ':ribbon:' => '🎀', ':tophat:' => '🎩', ':crown:' => '👑',
            ':womans_hat:'                      => '👒', ':mans_shoe:' => '👞', ':closed_umbrella:' => '🌂', ':briefcase:' => '💼',
            ':handbag:'                         => '👜', ':pouch:' => '👝', ':purse:' => '👛', ':eyeglasses:' => '👓',
            ':fishing_pole_and_fish:'           => '🎣', ':coffee:' => '☕️', ':tea:' => '🍵', ':sake:' => '🍶',
            ':baby_bottle:'                     => '🍼', ':beer:' => '🍺', ':beers:' => '🍻', ':cocktail:' => '🍸',
            ':tropical_drink:'                  => '🍹', ':wine_glass:' => '🍷', ':fork_and_knife:' => '🍴', ':pizza:' => '🍕',
            ':hamburger:'                       => '🍔', ':fries:' => '🍟', ':poultry_leg:' => '🍗', ':meat_on_bone:' => '🍖',
            ':spaghetti:'                       => '🍝', ':curry:' => '🍛', ':fried_shrimp:' => '🍤', ':bento:' => '🍱',
            ':sushi:'                           => '🍣', ':fish_cake:' => '🍥', ':rice_ball:' => '🍙', ':rice_cracker:' => '🍘',
            ':rice:'                            => '🍚', ':ramen:' => '🍜', ':stew:' => '🍲', ':oden:' => '🍢',
            ':dango:'                           => '🍡', ':egg:' => '🥚', ':bread:' => '🍞', ':doughnut:' => '🍩',
            ':custard:'                         => '🍮', ':icecream:' => '🍦', ':ice_cream:' => '🍨', ':shaved_ice:' => '🍧',
            ':birthday:'                        => '🎂', ':cake:' => '🍰', ':cookie:' => '🍪', ':chocolate_bar:' => '🍫',
            ':candy:'                           => '🍬', ':lollipop:' => '🍭', ':honey_pot:' => '🍯', ':apple:' => '🍎',
            ':green_apple:'                     => '🍏', ':tangerine:' => '🍊', ':lemon:' => '🍋', ':cherries:' => '🍒',
            ':grapes:'                          => '🍇', ':watermelon:' => '🍉', ':strawberry:' => '🍓', ':peach:' => '🍑',
            ':melon:'                           => '🍈', ':banana:' => '🍌', ':pear:' => '🍐', ':pineapple:' => '🍍',
            ':sweet_potato:'                    => '🍠', ':eggplant:' => '🍆', ':tomato:' => '🍅', ':corn:' => '🌽',
            ':house:'                           => '🏠', ':house_with_garden:' => '🏡', ':school:' => '🏫', ':office:' => '🏢',
            ':post_office:'                     => '🏣', ':hospital:' => '🏥', ':bank:' => '🏦', ':convenience_store:' => '🏪',
            ':love_hotel:'                      => '🏩', ':hotel:' => '🏨', ':wedding:' => '💒', ':church:' => '⛪️',
            ':department_store:'                => '🏬', ':european_post_office:' => '🏤', ':city_sunrise:' => '🌇', ':city_sunset:' => '🌆',
            ':japanese_castle:'                 => '🏯', ':european_castle:' => '🏰', ':tent:' => '⛺️', ':factory:' => '🏭',
            ':tokyo_tower:'                     => '🗼', ':japan:' => '🗾', ':mount_fuji:' => '🗻', ':sunrise_over_mountains:' => '🌄',
            ':sunrise:'                         => '🌅', ':stars:' => '🌠', ':statue_of_liberty:' => '🗽', ':bridge_at_night:' => '🌉',
            ':carousel_horse:'                  => '🎠', ':rainbow:' => '🌈', ':ferris_wheel:' => '🎡', ':fountain:' => '⛲️',
            ':roller_coaster:'                  => '🎢', ':ship:' => '🚢', ':speedboat:' => '🚤', ':boat:' => '⛵️',
            ':sailboat:'                        => '⛵️', ':rowboat:' => '🚣', ':anchor:' => '⚓️', ':rocket:' => '🚀',
            ':airplane:'                        => '✈️', ':helicopter:' => '🚁', ':steam_locomotive:' => '🚂', ':tram:' => '🚊',
            ':mountain_railway:'                => '🚞', ':bike:' => '🚲', ':aerial_tramway:' => '🚡', ':suspension_railway:' => '🚟',
            ':mountain_cableway:'               => '🚠', ':tractor:' => '🚜', ':blue_car:' => '🚙', ':oncoming_automobile:' => '🚘',
            ':car:'                             => '🚗', ':red_car:' => '🚗', ':taxi:' => '🚕', ':oncoming_taxi:' => '🚖',
            ':articulated_lorry:'               => '🚛', ':bus:' => '🚌', ':oncoming_bus:' => '🚍', ':rotating_light:' => '🚨',
            ':police_car:'                      => '🚓', ':oncoming_police_car:' => '🚔', ':fire_engine:' => '🚒', ':ambulance:' => '🚑',
            ':minibus:'                         => '🚐', ':truck:' => '🚚', ':train:' => '🚋', ':station:' => '🚉',
            ':train2:'                          => '🚆', ':bullettrain_front:' => '🚅', ':bullettrain_side:' => '🚄', ':light_rail:' => '🚈',
            ':monorail:'                        => '🚝', ':railway_car:' => '🚃', ':trolleybus:' => '🚎', ':ticket:' => '🎫',
            ':fuelpump:'                        => '⛽️', ':vertical_traffic_light:' => '🚦', ':traffic_light:' => '🚥', ':warning:' => '⚠️',
            ':construction:'                    => '🚧', ':beginner:' => '🔰', ':atm:' => '🏧', ':slot_machine:' => '🎰',
            ':busstop:'                         => '🚏', ':barber:' => '💈', ':hotsprings:' => '♨️', ':checkered_flag:' => '🏁',
            ':crossed_flags:'                   => '🎌', ':izakaya_lantern:' => '🏮', ':moyai:' => '🗿', ':circus_tent:' => '🎪',
            ':performing_arts:'                 => '🎭', ':round_pushpin:' => '📍', ':triangular_flag_on_post:' => '🚩', ':jp:' => '🇯🇵',
            ':kr:'                              => '🇰🇷', ':cn:' => '🇨🇳', ':us:' => '🇺🇸', ':fr:' => '🇫🇷',
            ':es:'                              => '🇪🇸', ':it:' => '🇮🇹', ':ru:' => '🇷🇺', ':gb:' => '🇬🇧',
            ':uk:'                              => '🇬🇧', ':de:' => '🇩🇪', ':one:' => '1️⃣', ':two:' => '2️⃣',
            ':three:'                           => '3️⃣', ':four:' => '4️⃣', ':five:' => '5️⃣', ':six:' => '6️⃣',
            ':seven:'                           => '7️⃣', ':eight:' => '8️⃣', ':nine:' => '9️⃣', ':keycap_ten:' => '🔟',
            ':1234:'                            => '🔢', ':zero:' => '0️⃣', ':hash:' => '#️⃣', ':symbols:' => '🔣',
            ':arrow_backward:'                  => '◀️', ':arrow_down:' => '⬇️', ':arrow_forward:' => '▶️', ':arrow_left:' => '⬅️',
            ':capital_abcd:'                    => '🔠', ':abcd:' => '🔡', ':abc:' => '🔤', ':arrow_lower_left:' => '↙️',
            ':arrow_lower_right:'               => '↘️', ':arrow_right:' => '➡️', ':arrow_up:' => '⬆️', ':arrow_upper_left:' => '↖️',
            ':arrow_upper_right:'               => '↗️', ':arrow_double_down:' => '⏬', ':arrow_double_up:' => '⏫', ':arrow_down_small:' => '🔽',
            ':arrow_heading_down:'              => '⤵️', ':arrow_heading_up:' => '⤴️', ':leftwards_arrow_with_hook:' => '↩️', ':arrow_right_hook:' => '↪️',
            ':left_right_arrow:'                => '↔️', ':arrow_up_down:' => '↕️', ':arrow_up_small:' => '🔼', ':arrows_clockwise:' => '🔃',
            ':arrows_counterclockwise:'         => '🔄', ':rewind:' => '⏪', ':fast_forward:' => '⏩', ':information_source:' => 'ℹ️',
            ':ok:'                              => '🆗', ':twisted_rightwards_arrows:' => '🔀', ':repeat:' => '🔁', ':repeat_one:' => '🔂',
            ':new:'                             => '🆕', ':top:' => '🔝', ':up:' => '🆙', ':cool:' => '🆒',
            ':free:'                            => '🆓', ':ng:' => '🆖', ':cinema:' => '🎦', ':koko:' => '🈁',
            ':signal_strength:'                 => '📶', ':u5272:' => '🈹', ':u5408:' => '🈴', ':u55b6:' => '🈺',
            ':u6307:'                           => '🈯️', ':u6708:' => '🈷️', ':u6709:' => '🈶', ':u6e80:' => '🈵',
            ':u7121:'                           => '🈚️', ':u7533:' => '🈸', ':u7a7a:' => '🈳', ':u7981:' => '🈲',
            ':sa:'                              => '🈂️', ':restroom:' => '🚻', ':mens:' => '🚹', ':womens:' => '🚺',
            ':baby_symbol:'                     => '🚼', ':no_smoking:' => '🚭', ':parking:' => '🅿️', ':wheelchair:' => '♿️',
            ':metro:'                           => '🚇', ':baggage_claim:' => '🛄', ':accept:' => '🉑', ':wc:' => '🚾',
            ':potable_water:'                   => '🚰', ':put_litter_in_its_place:' => '🚮', ':secret:' => '㊙️', ':congratulations:' => '㊗️',
            ':m:'                               => 'Ⓜ️', ':passport_control:' => '🛂', ':left_luggage:' => '🛅', ':customs:' => '🛃',
            ':ideograph_advantage:'             => '🉐', ':cl:' => '🆑', ':sos:' => '🆘', ':id:' => '🆔',
            ':no_entry_sign:'                   => '🚫', ':underage:' => '🔞', ':no_mobile_phones:' => '📵', ':do_not_litter:' => '🚯',
            ':non-potable_water:'               => '🚱', ':no_bicycles:' => '🚳', ':no_pedestrians:' => '🚷', ':children_crossing:' => '🚸',
            ':no_entry:'                        => '⛔️', ':eight_spoked_asterisk:' => '✳️', ':eight_pointed_black_star:' => '✴️', ':heart_decoration:' => '💟',
            ':vs:'                              => '🆚', ':vibration_mode:' => '📳', ':mobile_phone_off:' => '📴', ':chart:' => '💹',
            ':currency_exchange:'               => '💱', ':aries:' => '♈️', ':taurus:' => '♉️', ':gemini:' => '♊️',
            ':cancer:'                          => '♋️', ':leo:' => '♌️', ':virgo:' => '♍️', ':libra:' => '♎️',
            ':scorpius:'                        => '♏️', ':sagittarius:' => '♐️', ':capricorn:' => '♑️', ':aquarius:' => '♒️',
            ':pisces:'                          => '♓️', ':ophiuchus:' => '⛎', ':six_pointed_star:' => '🔯', ':negative_squared_cross_mark:' => '❎',
            ':a:'                               => '🅰️', ':b:' => '🅱️', ':ab:' => '🆎', ':o2:' => '🅾️',
            ':diamond_shape_with_a_dot_inside:' => '💠', ':recycle:' => '♻️', ':end:' => '🔚', ':on:' => '🔛',
            ':soon:'                            => '🔜', ':clock1:' => '🕐', ':clock130:' => '🕜', ':clock10:' => '🕙',
            ':clock1030:'                       => '🕥', ':clock11:' => '🕚', ':clock1130:' => '🕦', ':clock12:' => '🕛',
            ':clock1230:'                       => '🕧', ':clock2:' => '🕑', ':clock230:' => '🕝', ':clock3:' => '🕒',
            ':clock330:'                        => '🕞', ':clock4:' => '🕓', ':clock430:' => '🕟', ':clock5:' => '🕔',
            ':clock530:'                        => '🕠', ':clock6:' => '🕕', ':clock630:' => '🕡', ':clock7:' => '🕖',
            ':clock730:'                        => '🕢', ':clock8:' => '🕗', ':clock830:' => '🕣', ':clock9:' => '🕘',
            ':clock930:'                        => '🕤', ':heavy_dollar_sign:' => '💲', ':copyright:' => '©️', ':registered:' => '®️',
            ':tm:'                              => '™️', ':x:' => '❌', ':heavy_exclamation_mark:' => '❗️', ':bangbang:' => '‼️',
            ':interrobang:'                     => '⁉️', ':o:' => '⭕️', ':heavy_multiplication_x:' => '✖️', ':heavy_plus_sign:' => '➕',
            ':heavy_minus_sign:'                => '➖', ':heavy_division_sign:' => '➗', ':white_flower:' => '💮', ':100:' => '💯',
            ':heavy_check_mark:'                => '✔️', ':ballot_box_with_check:' => '☑️', ':radio_button:' => '🔘', ':link:' => '🔗',
            ':curly_loop:'                      => '➰', ':wavy_dash:' => '〰️', ':part_alternation_mark:' => '〽️', ':trident:' => '🔱',
            ':white_check_mark:'                => '✅', ':black_square_button:' => '🔲', ':white_square_button:' => '🔳', ':black_circle:' => '⚫️',
            ':white_circle:'                    => '⚪️', ':red_circle:' => '🔴', ':large_blue_circle:' => '🔵', ':large_blue_diamond:' => '🔷',
            ':large_orange_diamond:'            => '🔶', ':small_blue_diamond:' => '🔹', ':small_orange_diamond:' => '🔸', ':small_red_triangle:' => '🔺',
            ':small_red_triangle_down:'         => '🔻', ':black_small_square:' => '▪️', ':black_medium_small_square:' => '◾', ':black_medium_square:' => '◼️',
            ':black_large_square:'              => '⬛', ':white_small_square:' => '▫️', ':white_medium_small_square:' => '◽', ':white_medium_square:' => '◻️',
            ':white_large_square:'              => '⬜',
        ];

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'text' => \str_replace(\array_keys($emojiMap), $emojiMap, $matches[0]),
            ],
        ];
    }

    /**
     * Handle marks
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineMark(array $excerpt) : ?array
    {
        if (!\str_starts_with($excerpt['text'], '==')
            || \preg_match('/^(==)([^=]*?)(==)/', $excerpt['text'], $matches) !== 1
        ) {
            return null;
        }

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'name' => 'mark',
                'text' => $matches[2],
            ],
        ];
    }

    /**
     * Handle marks
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineSpoiler(array $excerpt) : ?array
    {
        if (!\str_starts_with($excerpt['text'], '>!')
            || \preg_match('/^>!(.*?)!</us', $excerpt['text'], $matches) !== 1
        ) {
            return null;
        }

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'name'       => 'span',
                'attributes' => [
                    'class' => 'spoiler',
                ],
                'elements' => [
                    [
                        'name'       => 'input',
                        'attributes' => [
                            'type' => 'checkbox',
                        ],
                    ],
                    [
                        'name' => 'span',
                        'text' => $matches[1],
                    ],
                ],
            ],
        ];
    }

    /**
     * Handle keystrokes
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineKeystrokes(array $excerpt) : ?array
    {
        if (!\str_starts_with($excerpt['text'], '[[')
            || \preg_match('/^(?<!\[)(?:\[\[([^\[\]]*|[\[\]])\]\])(?!\])/s', $excerpt['text'], $matches) !== 1
        ) {
            return null;
        }

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'name' => 'kbd',
                'text' => $matches[1],
            ],
        ];
    }

    /**
     * Handle embedding
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineEmbedding(array $excerpt) : ?array
    {
        $video = false;
        $audio = false;

        if (!($this->options['embedding'] ?? false)
            || (!\str_starts_with($excerpt['text'], '[video') && !\str_starts_with($excerpt['text'], '[audio'))
            || (!($video = (\preg_match('/\[video.*src="([^"]*)".*\]/', $excerpt['text'], $matches) === 1))
                && !($audio = (\preg_match('/\[audio.*src="([^"]*)".*\]/', $excerpt['text'], $matches) === 1)))
        ) {
            return null;
        }

        $url = $matches[1];
        if ($video) {
            $type = '';

            $needles = ['youtube', 'vimeo', 'dailymotion'];
            foreach ($needles as $needle) {
                if (\strpos($url, $needle) !== false) {
                    $type = $needle;
                }
            }

            switch ($type) {
                case 'youtube':
                    $element    = 'iframe';
                    $attributes = [
                        'src'             => \preg_replace('/.*\?v=([^\&\]]*).*/', 'https://www.youtube.com/embed/$1', $url),
                        'frameborder'     => '0',
                        'allow'           => 'autoplay',
                        'allowfullscreen' => '',
                        'sandbox'         => 'allow-same-origin allow-scripts allow-forms',
                    ];
                    break;
                case 'vimeo':
                    $element    = 'iframe';
                    $attributes = [
                        'src'             => \preg_replace('/(?:https?:\/\/(?:[\w]{3}\.|player\.)*vimeo\.com(?:[\/\w:]*(?:\/videos)?)?\/([0-9]+)[^\s]*)/', 'https://player.vimeo.com/video/$1', $url),
                        'frameborder'     => '0',
                        'allow'           => 'autoplay',
                        'allowfullscreen' => '',
                        'sandbox'         => 'allow-same-origin allow-scripts allow-forms',
                    ];
                    break;
                case 'dailymotion':
                    $element    = 'iframe';
                    $attributes = [
                        'src'             => $url,
                        'frameborder'     => '0',
                        'allow'           => 'autoplay',
                        'allowfullscreen' => '',
                        'sandbox'         => 'allow-same-origin allow-scripts allow-forms',
                    ];
                    break;
                default:
                    $element    = 'video';
                    $attributes = [
                        'src'      => UriFactory::build($url),
                        'controls' => '',
                    ];
            }

            return [
                'extent'  => \strlen($matches[0]),
                'element' => [
                    'name'       => $element,
                    'text'       => $matches[1],
                    'attributes' => $attributes,
                ],
            ];
        } elseif ($audio) {
            return [
                'extent'  => \strlen($matches[0]),
                'element' => [
                    'name'       => 'audio',
                    'text'       => $matches[1],
                    'attributes' => [
                        'src'      => UriFactory::build($url),
                        'controls' => '',
                    ],
                ],
            ];
        }

        return null;
    }

    /**
     * Handle map
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineMap(array $excerpt) : ?array
    {
        if (!($this->options['map'] ?? false)
            || !\str_starts_with($excerpt['text'], '[map')
            || (\preg_match('/\[map(?:\s+(?:name="([^"]+)"|country="([^"]+)"|city="([^"]+)"|zip="([^"]+)"|address="([^"]+)"|lat="([^"]+)"|lon="([^"]+)")){0,7}\]/', $excerpt['text'], $matches) !== 1)
        ) {
            return null;
        }

        $name    = $matches[1];
        $country = $matches[2];
        $city    = $matches[3];
        $zip     = $matches[4];
        $address = $matches[5];

        $lat = $matches[6];
        $lon = $matches[7];

        if ($lat === '' || $lon === '') {
            [$lat, $lon] = \phpOMS\Api\Geocoding\Nominatim::geocoding($country, $city, $address, $zip);
        }

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'name'       => 'div',
                'text'       => '',
                'attributes' => [
                    'id'       => 'i' . \bin2hex(\random_bytes(4)),
                    'class'    => 'map',
                    'data-lat' => $lat,
                    'data-lon' => $lon,
                ],
            ],
        ];
    }

    /**
     * Handle address
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineAddress(array $excerpt) : ?array
    {
        if (!($this->options['address'] ?? false)
            || !\str_starts_with($excerpt['text'], '[addr')
            || (\preg_match('/\[addr(?:\s+(?:name="([^"]+)"|country="([^"]+)"|city="([^"]+)"|zip="([^"]+)"|address="([^"]+)")){0,5}\]/', $excerpt['text'], $matches) !== 1)
        ) {
            return null;
        }

        $name    = $matches[1];
        $country = $matches[2];
        $city    = $matches[3];
        $zip     = $matches[4];
        $address = $matches[5];

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'name' => 'div',
                //'text' => '',
                'attributes' => [
                    'class' => 'addressWidget',
                ],
                'elements' => [
                    [
                        'name'       => 'span',
                        'text'       => $name,
                        'attributes' => ['class' => 'addressWidget-name'],
                    ],
                    [
                        'name'       => 'span',
                        'text'       => $address,
                        'attributes' => ['class' => 'addressWidget-address'],
                    ],
                    [
                        'name'       => 'span',
                        'text'       => $zip,
                        'attributes' => ['class' => 'addressWidget-zip'],
                    ],
                    [
                        'name'       => 'span',
                        'text'       => $city,
                        'attributes' => ['class' => 'addressWidget-city'],
                    ],
                    [
                        'name'       => 'span',
                        'text'       => $country,
                        'attributes' => ['class' => 'addressWidget-country'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Handle contact
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineContact(array $excerpt) : ?array
    {
        if (!($this->options['contact'] ?? false)
            || !\str_starts_with($excerpt['text'], '[contact')
            || (\preg_match('/\[contact.*?([a-zA-Z]+)="(.*?)"\]/', $excerpt['text'], $matches) !== 1)
        ) {
            return null;
        }

        $src = '';
        switch ($matches[1]) {
            case 'email':
                $src = 'Resources/icons/company/email.svg';
                break;
            case 'phone':
                $src = 'Resources/icons/company/phone.svg';
                break;
            case 'twitter':
                $src = 'Resources/icons/company/twitter.svg';
                break;
            case 'instagram':
                $src = 'Resources/icons/company/instagram.svg';
                break;
            case 'discord':
                $src = 'Resources/icons/company/discord.svg';
                break;
            case 'slack':
                $src = 'Resources/icons/company/slack.svg';
                break;
            case 'teams':
                $src = 'Resources/icons/company/teams.svg';
                break;
            case 'facebook':
                $src = 'Resources/icons/company/facebook.svg';
                break;
            case 'youtube':
                $src = 'Resources/icons/company/youtube.svg';
                break;
            case 'paypal':
                $src = 'Resources/icons/company/paypal.svg';
                break;
            case 'linkedin':
                $src = 'Resources/icons/company/linkedin.svg';
                break;
        }

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'name' => 'a',
                //'text' => '',
                'attributes' => [
                    'class' => 'contactWidget',
                    'href'  => '',
                ],
                'elements' => [
                    [
                        'name'       => 'img',
                        'attributes' => [
                            'class' => 'contactWidget-icon',
                            'src'   => $src,
                        ],
                    ],
                    [
                        'name'       => 'span',
                        'text'       => $matches[2],
                        'attributes' => ['class' => 'contactWidget-contact'],
                    ],
                ],

            ],
        ];
    }

    /**
     * Handle progress
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineProgress(array $excerpt) : ?array
    {
        if (!($this->options['progress'] ?? false)
            || !\str_starts_with($excerpt['text'], '[progress')
            || (\preg_match('/\[progress(?:\s+(?:type="([^"]+)"|percent="([^"]+)"|value="([^"]+)")){0,3}\]/', $excerpt['text'], $matches) !== 1)
        ) {
            return null;
        }

        // $type = empty($matches[1]) ? 'meter' : $matches[1];
        $percent = empty($matches[2]) ? $matches[3] : $matches[2];
        $value   = empty($matches[3]) ? $matches[2] : $matches[3];

        if ($percent === ''
            || $value === ''
        ) {
            return null;
        }

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'name'       => 'progress',
                'text'       => '',
                'attributes' => [
                    'value' => $value,
                    'max'   => '100',
                ],
            ],
        ];
    }

    /**
     * Handle super script
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineSuperscript(array $excerpt) : ?array
    {
        if (\preg_match('/(?:\^(?!\^)([^\^ ]*)\^(?!\^))/', $excerpt['text'], $matches) !== 1) {
            return null;
        }

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'name'     => 'sup',
                'text'     => $matches[1],
                'function' => 'lineElements',
            ],
        ];
    }

    /**
     * Handle sub script
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineSubscript(array $excerpt) : ?array
    {
        if (\preg_match('/(?:~(?!~)([^~ ]*)~(?!~))/', $excerpt['text'], $matches) !== 1) {
            return null;
        }

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'name'     => 'sub',
                'text'     => $matches[1],
                'function' => 'lineElements',
            ],
        ];
    }

    /**
     * Handle typographer
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineTypographer(array $excerpt) : ?array
    {
        if (\preg_match('/\+-|\(p\)|\(tm\)|\(r\)|\(c\)|\.{2,}|\!\.{3,}|\?\.{3,}/i', $excerpt['text'], $matches) !== 1) {
            return null;
        }

        $substitutions = [
            '/\(c\)/i'        => '&copy;',
            '/\(r\)/i'        => '&reg;',
            '/\(tm\)/i'       => '&trade;',
            '/\(p\)/i'        => '&para;',
            '/\+-/i'          => '&plusmn;',
            '/\.{4,}|\.{2}/i' => '...',
            '/\!\.{3,}/i'     => '!..',
            '/\?\.{3,}/i'     => '?..',
        ];

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'rawHtml' => \preg_replace(\array_keys($substitutions), \array_values($substitutions), $matches[0]),
            ],
        ];
    }

    /**
     * Handle math
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineMath(array $excerpt) : ?array
    {
        $matchSingleDollar = $this->options['math']['single_dollar'] ?? false;
        if ($matchSingleDollar) {
            // Match single dollar - experimental
            if (\preg_match('/^(?<!\\\\)((?<!\$)\$(?!\$)(.*?)(?<!\$)\$(?!\$)|(?<!\\\\\()\\\\\((.*?)(?<!\\\\\()\\\\\)(?!\\\\\)))/s', $excerpt['text'], $matches)) {
                $mathMatch = $matches[0];
            }
        } elseif (\preg_match('/^(?<!\\\\\()\\\\\((.*?)(?<!\\\\\()\\\\\)(?!\\\\\))/s', $excerpt['text'], $matches)) {
            $mathMatch = $matches[0];
        }

        if (!isset($mathMatch)) {
            return null;
        }

        return [
            'extent'  => \strlen($mathMatch),
            'element' => [
                'text' => $mathMatch,
            ],
        ];
    }

    /**
     * Handle escape sequence
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineEscapeSequence(array $excerpt) : ?array
    {
        if (!isset($excerpt['text'][1])
            || !\in_array($excerpt['text'][1], $this->specialCharacters)
        ) {
            return null;
        }

        $state = $this->options['math'] ?? false;
        if (!$state
            || !\preg_match('/^(?<!\\\\)(?<!\\\\\()\\\\\((.{2,}?)(?<!\\\\\()\\\\\)(?!\\\\\))/s', $excerpt['text'])
        ) {
            return [
                'extent'  => 2,
                'element' => [
                    'rawHtml' => $excerpt['text'][1],
                ],
            ];
        }

        return null;
    }

    /**
     * Handle block footnote
     *
     * @param array{body:string, indent:int, text:string} $line Line data
     * @param null|array                                  $_    Current block (unused parameter)
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockFootnote(array $line, ?array $_ = null) : ?array
    {
        return ($this->options['footnotes'] ?? true)
            && \preg_match('/^\[\^(.+?)\]:[ ]?(.*)$/', $line['text'], $matches) == 1
            ? [
                'label'  => $matches[1],
                'text'   => $matches[2],
                'hidden' => true,
            ]
            : null;
    }

    /**
     * Handle block definition list
     *
     * @param array{body:string, indent:int, text:string} $line  Line data
     * @param null|array                                  $block Current block
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockDefinitionList(array $line, ?array $block = null) : ?array
    {
        if (!($this->options['definition_lists'] ?? true)
            || $block === null
            || $block['type'] !== 'Paragraph'
        ) {
            return null;
        }

        $element = [
            'name'     => 'dl',
            'elements' => [],
        ];

        $terms = \explode("\n", $block['element']['handler']['argument']);

        foreach ($terms as $term) {
            $element['elements'][] = [
                'name'    => 'dt',
                'handler' => [
                    'function'    => 'lineElements',
                    'argument'    => $term,
                    'destination' => 'elements',
                ],
            ];
        }

        $block['element'] = $element;

        return $this->addDdElement($line, $block);
    }

    /**
     * Handle block code
     *
     * @param array{body:string, indent:int, text:string} $line  Line data
     * @param null|array                                  $block Current block
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockCode(array $line, ?array $block = null) : ?array
    {
        if (!($this->options['code']['blocks'] ?? true)
            || !($this->options['code'] ?? true)
            || ($block !== null && $block['type'] === 'Paragraph' && !isset($block['interrupted']))
            || $line['indent'] < 4
        ) {
            return null;
        }

        return [
            'element' => [
                'name'    => 'pre',
                'element' => [
                    'name' => 'code',
                    'text' => \substr($line['body'], 4),
                ],
            ],
        ];
    }

    /**
     * Handle block comment
     *
     * @param array{body:string, indent:int, text:string} $line Line data
     * @param null|array                                  $_    Current block (unused parameter)
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockComment(array $line, ?array $_ = null) : ?array
    {
        if (!($this->options['comments'] ?? true)
            || $this->markupEscaped || $this->safeMode
            || !\str_starts_with($line['text'], '<!--')
        ) {
            return null;
        }

        $block = [
            'element' => [
                'rawHtml'   => $line['body'],
                'autobreak' => true,
            ],
        ];

        if (\strpos($line['text'], '-->') !== false) {
            $block['closed'] = true;
        }

        return $block;
    }

    /**
     * Handle block comment
     *
     * @param array{body:string, indent:int, text:string} $line Line data
     * @param null|array                                  $_    Current block (unused parameter)
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockHeader(array $line, ?array $_ = null) : ?array
    {
        if (!($this->options['headings'] ?? true)) {
            return null;
        }

        $level = \strspn($line['text'], '#');
        if ($level > 6) {
            return null;
        }

        $text = \trim($line['text'], '#');
        if ($this->strictMode && isset($text[0]) && $text[0] !== ' ') {
            return null;
        }

        $text = \trim($text, ' ');

        $block = [
            'element' => [
                'name'    => 'h' . $level,
                'handler' => [
                    'function'    => 'lineElements',
                    'argument'    => $text,
                    'destination' => 'elements',
                ],
            ],
        ];

        if (\preg_match('/[ #]*{(' . $this->regexAttribute . '+)}[ ]*$/', $block['element']['handler']['argument'], $matches, \PREG_OFFSET_CAPTURE)) {
            $attributeString = $matches[1][0];

            $block['element']['attributes']          = $this->parseAttributeData($attributeString);
            $block['element']['handler']['argument'] = \substr($block['element']['handler']['argument'], 0, (int) $matches[0][1]);
        }

        // Get the text of the heading
        if (isset($block['element']['handler']['argument'])) {
            $text = $block['element']['handler']['argument'];
        }

        // Get the heading level. Levels are h1, h2, ..., h6
        $level = $block['element']['name'];

        $headersAllowed = $this->options['headings']['allowed'] ?? ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
        if (!\in_array($level, $headersAllowed)) {
            return null;
        }

        // Checks if auto generated anchors is allowed
        $autoAnchors = $this->options['headings']['auto_anchors'] ?? true;

        $id = $block['element']['attributes']['id'] ?? ($autoAnchors ? $this->createAnchorID($text) : null);

        // Set attributes to head tags
        $block['element']['attributes']['id'] = $id;

        $tocHeaders = $this->options['toc']['headings'] ?? ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
        // Check if level are defined as a heading
        if (\in_array($level, $tocHeaders)) {
            // Add/stores the heading element info to the ToC list
            $this->setContentsList([
                'text'  => $text,
                'id'    => $id,
                'level' => $level,
            ]);
        }

        return $block;
    }

    /**
     * Handle block list
     *
     * @param array{body:string, indent:int, text:string} $line    Line data
     * @param null|array                                  $current Current block
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockList(array $line, ?array $current = null) : ?array
    {
        if (!($this->options['lists'] ?? true)) {
            return null;
        }

        [$name, $pattern] = $line['text'][0] <= '-' ? ['ul', '[*+-]'] : ['ol', '[0-9]{1,9}+[.\)]'];

        if (\preg_match('/^(' . $pattern . '([ ]++|$))(.*+)/', $line['text'], $matches) !== 1) {
            return null;
        }

        $contentIndent = \strlen($matches[2]);
        if ($contentIndent >= 5) {
            --$contentIndent;

            $matches[1] = \substr($matches[1], 0, -$contentIndent);
            $matches[3] = \str_repeat(' ', $contentIndent) . $matches[3];
        } elseif ($contentIndent === 0) {
            $matches[1] .= ' ';
        }

        $markerWithoutWhitespace = \strstr($matches[1], ' ', true);
        if ($markerWithoutWhitespace === false) {
            $markerWithoutWhitespace = $matches[1];
        }

        if ($name !== 'ul') {
            $markerWithoutWhitespace = \substr($markerWithoutWhitespace, -1);

            if ($markerWithoutWhitespace === false) {
                $markerWithoutWhitespace = $matches[1];
            }
        }

        $block = [
            'indent'  => $line['indent'],
            'pattern' => $pattern,
            'data'    => [
                'type'       => $name,
                'marker'     => $matches[1],
                'markerType' => $markerWithoutWhitespace,
            ],
            'element' => [
                'name'     => $name,
                'elements' => [],
            ],
        ];

        $block['data']['markerTypeRegex'] = \preg_quote($block['data']['markerType'], '/');

        if ($name === 'ol') {
            $tmp = \strstr($matches[1], $block['data']['markerType'], true);
            if ($tmp === false) {
                $tmp = $matches[1];
            }

            $listStart = \ltrim($tmp, '0') ?: '0';

            if ($listStart !== '0') {
                if (isset($current)
                    && $current['type'] === 'Paragraph'
                    && !isset($current['interrupted'])
                ) {
                    return null;
                }

                $block['element']['attributes'] = ['start' => $listStart];
            }
        }

        $block['li'] = [
            'name'    => 'li',
            'handler' => [
                'function'    => 'li',
                'argument'    => empty($matches[3]) ? [] : [$matches[3]],
                'destination' => 'elements',
            ],
        ];

        $block['element']['elements'][] = &$block['li'];

        return $block;
    }

    /**
     * Handle block quote
     *
     * @param array{body:string, indent:int, text:string} $line Line data
     * @param null|array                                  $_    Current block (unused parameter)
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockQuote(array $line, ?array $_ = null) : ?array
    {
        if (!($this->options['qoutes'] ?? true)
            || \preg_match('/^>[ ]?+(.*+)/', $line['text'], $matches) !== 1
        ) {
            return null;
        }

        return [
            'element' => [
                'name'    => 'blockquote',
                'handler' => [
                    'function'    => 'linesElements',
                    'argument'    => (array) $matches[1],
                    'destination' => 'elements',
                ],
            ],
        ];
    }

    /**
     * Handle block rule
     *
     * @param array{body:string, indent:int, text:string} $line Line data
     * @param null|array                                  $_    Current block (unused parameter)
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockRule(array $line, ?array $_ = null) : ?array
    {
        if (!($this->options['thematic_breaks'] ?? true)) {
            return null;
        }

        $marker = $line['text'][0];
        if (\substr_count($line['text'], $marker) >= 3 && \rtrim($line['text'], " {$marker}") === '') {
            return [
                'element' => [
                    'name' => 'hr',
                ],
            ];
        }

        return null;
    }

    /**
     * Handle block header
     *
     * @param array{body:string, indent:int, text:string} $line  Line data
     * @param null|array                                  $block Current block
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockSetextHeader(array $line, ?array $block = null) : ?array
    {
        if (!($this->options['headings'] ?? true)
            || $block === null
            || $block['type'] !== 'Paragraph'
            || isset($block['interrupted'])
        ) {
            return null;
        }

        if ($line['indent'] < 4 && \rtrim(\rtrim($line['text'], ' '), $line['text'][0]) === '') {
            $block['element']['name'] = $line['text'][0] === '=' ? 'h1' : 'h2';
        }

        if (\preg_match('/[ ]*{(' . $this->regexAttribute . '+)}[ ]*$/', $block['element']['handler']['argument'], $matches, \PREG_OFFSET_CAPTURE)) {
            $attributeString = $matches[1][0];

            $block['element']['attributes']          = $this->parseAttributeData($attributeString);
            $block['element']['handler']['argument'] = \substr($block['element']['handler']['argument'], 0, (int) $matches[0][1]);
        }

        // Get the text of the heading
        $text = null;
        if (isset($block['element']['handler']['argument'])) {
            $text = $block['element']['handler']['argument'];
        }

        // Get the heading level. Levels are h1, h2, ..., h6
        $level = $block['element']['name'];

        $headersAllowed = $this->options['headings']['allowed'] ?? ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
        if (!\in_array($level, $headersAllowed)) {
            return null;
        }

        // Checks if auto generated anchors is allowed
        $autoAnchors = $this->options['headings']['auto_anchors'] ?? true;

        $id = $block['element']['attributes']['id'] ?? ($autoAnchors ? $this->createAnchorID($text) : null);

        // Set attributes to head tags
        $block['element']['attributes']['id'] = $id;

        $headersAllowed = $this->options['headings']['allowed'] ?? ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];

        // Check if level are defined as a heading
        if (\in_array($level, $headersAllowed)) {
            // Add/stores the heading element info to the ToC list
            $this->setContentsList([
                'text'  => $text,
                'id'    => $id,
                'level' => $level,
            ]);
        }

        return $block;
    }

    /**
     * Handle block markup
     *
     * @param array{body:string, indent:int, text:string} $line Line data
     * @param null|array                                  $_    Current block (unused parameter)
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockMarkup(array $line, ?array $_ = null) : ?array
    {
        if (!($this->options['markup'] ?? true)
            || $this->markupEscaped || $this->safeMode
            || \preg_match('/^<(\w[\w-]*)(?:[ ]*' . $this->regexHtmlAttribute . ')*[ ]*(\/)?>/', $line['text'], $matches) !== 1
        ) {
            return null;
        }

        $element = \strtolower($matches[1]);

        if (\in_array($element, $this->textLevelElements)) {
            return null;
        }

        $block = [
            'name'    => $matches[1],
            'depth'   => 0,
            'element' => [
                'rawHtml'   => $line['text'],
                'autobreak' => true,
            ],
        ];

        $length    = \strlen($matches[0]);
        $remainder = \substr($line['text'], $length);

        if (\trim($remainder) === '') {
            if (isset($matches[2]) || \in_array($matches[1], $this->voidElements)) {
                $block['closed'] = true;
                $block['void']   = true;
            }
        } else {
            if (isset($matches[2]) || \in_array($matches[1], $this->voidElements)) {
                return null;
            }

            if (\preg_match('/<\/' . $matches[1] . '>[ ]*$/i', $remainder)) {
                $block['closed'] = true;
            }
        }

        return $block;
    }

    /**
     * Handle block reference
     *
     * @param array{body:string, indent:int, text:string} $line Line data
     * @param null|array                                  $_    Current block (unused parameter)
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockReference(array $line, ?array $_ = null) : ?array
    {
        if (!($this->options['references'] ?? true)
            || \strpos($line['text'], ']') === false
            || \preg_match('/^\[(.+?)\]:[ ]*+<?(\S+?)>?(?:[ ]+["\'(](.+)["\')])?[ ]*+$/', $line['text'], $matches) !== 1

        ) {
            return null;
        }

        $id = \strtolower($matches[1]);

        $this->definitionData['Reference'][$id] = [
            'url'   => UriFactory::build($matches[2]),
            'title' => isset($matches[3]) ? $matches[3] : null,
        ];

        return [
            'element' => [],
        ];
    }

    /**
     * Handle block table
     *
     * @param array{body:string, indent:int, text:string} $line  Line data
     * @param null|array                                  $block Current block
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockTable(array $line, ?array $block = null) : ?array
    {
        if (!($this->options['tables'] ?? true)
            || $block === null || $block['type'] !== 'Paragraph' || isset($block['interrupted'])
            || (\strpos($block['element']['handler']['argument'], '|') === false
                && \strpos($line['text'], '|') === false
                && \strpos($line['text'], ':') === false
                || \strpos($block['element']['handler']['argument'], "\n") !== false)
            || \rtrim($line['text'], ' -:|') !== ''
        ) {
            return null;
        }

        $alignments = [];

        $divider = $line['text'];
        $divider = \trim($divider);
        $divider = \trim($divider, '|');

        $dividerCells = \explode('|', $divider);

        foreach ($dividerCells as $dividerCell) {
            $dividerCell = \trim($dividerCell);

            if ($dividerCell === '') {
                return null;
            }

            $alignment = null;

            if ($dividerCell[0] === ':') {
                $alignment = 'left';
            }

            if (\substr($dividerCell, - 1) === ':') {
                $alignment = $alignment === 'left' ? 'center' : 'right';
            }

            $alignments [] = $alignment;
        }

        $headerElements = [];

        $header = $block['element']['handler']['argument'];
        $header = \trim($header);
        $header = \trim($header, '|');

        $headerCells = \explode('|', $header);

        if (\count($headerCells) !== \count($alignments)) {
            return null;
        }

        foreach ($headerCells as $index => $headerCell) {
            $headerCell = \trim($headerCell);

            $headerElement = [
                'name'    => 'th',
                'handler' => [
                    'function'    => 'lineElements',
                    'argument'    => $headerCell,
                    'destination' => 'elements',
                ],
            ];

            if (isset($alignments[$index])) {
                $alignment = $alignments[$index];

                $headerElement['attributes'] = [
                    'style' => "text-align: {$alignment};",
                ];
            }

            $headerElements[] = $headerElement;
        }

        $block = [
            'alignments' => $alignments,
            'identified' => true,
            'element'    => [
                'name'     => 'table',
                'elements' => [],
            ],
        ];

        $block['element']['elements'][] = [
            'name' => 'thead',
        ];

        $block['element']['elements'][] = [
            'name'     => 'tbody',
            'elements' => [],
        ];

        $block['element']['elements'][0]['elements'][] = [
            'name'     => 'tr',
            'elements' => $headerElements,
        ];

        return $block;
    }

    /**
     * Handle block abbreviation
     *
     * @param array{body:string, indent:int, text:string} $line Line data
     * @param null|array                                  $_    Current block (unused parameter)
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockAbbreviation(array $line, ?array $_ = null) : ?array
    {
        if (!($this->options['abbreviations'] ?? true)) {
            return null;
        }

        $allowCustomAbbr = $this->options['abbreviations']['allow_custom_abbr'] ?? true;

        if (isset($this->options['abbreviations']['predefine'])) {
            foreach ($this->options['abbreviations']['predefine'] as $abbreviations => $description) {
                $this->definitionData['Abbreviation'][$abbreviations] = $description;
            }
        }

        if (!$allowCustomAbbr
            || \preg_match('/^\*\[(.+?)\]:[ ]*(.+?)[ ]*$/', $line['text'], $matches) !== 1
        ) {
            return null;
        }

        $this->definitionData['Abbreviation'][$matches[1]] = $matches[2];

        return [
            'hidden' => true,
        ];
    }

    /**
     * Handle block math
     *
     * @param array{body:string, indent:int, text:string} $line Line data
     * @param null|array                                  $_    Current block (unused parameter)
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockMath(array $line, ?array $_ = null) : ?array
    {
        $block = [
            'element' => [
                'text' => '',
            ],
        ];

        if (\preg_match('/^(?<!\\\\)(\\\\\[)(?!.)$/', $line['text'])) {
            $block['end'] = '\]';

            return $block;
        }

        if (\preg_match('/^(?<!\\\\)(\$\$)(?!.)$/', $line['text'])) {
            $block['end'] = '$$';

            return $block;
        }

        return null;
    }

    /**
     * Continue block math
     *
     * @param array{body:string, indent:int, text:string} $line  Line data
     * @param array                                       $block Current block
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockMathContinue(array $line, array $block) : ?array
    {
        if (isset($block['complete'])) {
            return null;
        }

        if (isset($block['interrupted'])) {
            $block['element']['text'] .= \str_repeat("\n", $block['interrupted']);

            unset($block['interrupted']);
        }

        if (\preg_match('/^(?<!\\\\)(\\\\\])$/', $line['text']) && $block['end'] === '\]') {
            $block['complete']        = true;
            $block['math']            = true;
            $block['element']['text'] = '\\[' . $block['element']['text'] . '\\]';

            return $block;
        }

        if (\preg_match('/^(?<!\\\\)(\$\$)$/', $line['text']) && $block['end'] === '$$') {
            $block['complete']        = true;
            $block['math']            = true;
            $block['element']['text'] = '$$' . $block['element']['text'] . '$$';

            return $block;
        }

        $block['element']['text'] .= "\n" . $line['body'];

        return $block;
    }

    /**
     * Complete block math
     *
     * @param array $block Current block
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function blockMathComplete(array $block) : array
    {
        return $block;
    }

    /**
     * Continue block math
     *
     * @param array{body:string, indent:int, text:string} $line Line data
     * @param null|array                                  $_    Current block (unused parameter)
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockFencedCode(array $line, ?array $_ = null) : ?array
    {
        if (!($this->options['code']['blocks'] ?? true)
            || !($this->options['code'] ?? true)
        ) {
            return null;
        }

        $marker       = $line['text'][0];
        $openerLength = \strspn($line['text'], $marker);

        if ($openerLength < 3) {
            return null;
        }

        $language = \trim(\preg_replace('/^`{3}([^\s]+)(.+)?/s', '$1', $line['text']) ?? '');

        if (!($this->options['diagrams'] ?? true)
            || !\in_array($language, ['mermaid', 'chartjs', 'tuichart'])
        ) {
            // Is code block
            $element = [
                'name' => 'code',
                'text' => '',
            ];

            if ($language !== '```' && !empty($language)) {
                $element['attributes'] = ['class' => "language-{$language}"];
            }

            return [
                'char'         => $marker,
                'openerLength' => $openerLength,
                'element'      => [
                    'name'    => 'pre',
                    'element' => $element,
                ],
            ];
        } elseif (\strtolower($language) === 'chartjs') {
            // Chart.js https://www.chartjs.org/
            $element = [
                'text' => '',
            ];

            return [
                'char'         => $marker,
                'openerLength' => $openerLength,
                'element'      => [
                    'element'    => $element,
                    'name'       => 'canvas',
                    'attributes' => [
                        'class' => 'chartjs',
                    ],
                ],
            ];
        } elseif (\in_array(\strtolower($language), ['mermaid', 'tuichart'])) {
            // Mermaid.js https://mermaidjs.github.io
            // TUI.chart https://github.com/nhn/tui.chart
            $element = [
                'text' => '',
            ];

            return [
                'char'         => $marker,
                'openerLength' => $openerLength,
                'element'      => [
                    'element'    => $element,
                    'name'       => 'div',
                    'attributes' => [
                        'class' => \strtolower($language),
                    ],
                ],
            ];
        }

        return null;
    }

    /**
     * Continue block spoiler
     *
     * @param array{body:string, indent:int, text:string} $line Line data
     * @param null|array                                  $_    Current block (unused parameter)
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockSpoiler(array $line, ?array $_ = null) : ?array
    {
        if (!($this->options['code']['blocks'] ?? true)
            || !($this->options['code'] ?? true)
        ) {
            return null;
        }

        $marker       = $line['text'][0];
        $openerLength = \strspn($line['text'], $marker);

        if ($openerLength < 3) {
            return null;
        }

        $summary = \trim(\preg_replace('/^\?{3}(.+)?/s', '$1', $line['text']) ?? '');

        $infostring = \trim(\substr($line['text'], $openerLength), "\t ");
        if (\strpos($infostring, '?') !== false) {
            return null;
        }

        // @performance Optimize away the child <span> element for spoilers (if reasonable)
        //      https://github.com/Karaka-Management/phpOMS/issues/367
        return [
            'char'         => $marker,
            'openerLength' => $openerLength,
            'element'      => [
                'name'    => 'details',
                'element' => [
                    'text'     => '',
                    'elements' => [
                        [
                            'name' => 'summary',
                            'text' => $summary,
                        ],
                        [
                            'name' => 'span',
                            'text' => '',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Complete block table
     *
     * @param array $block Current block
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockTableComplete(array $block) : ?array
    {
        if (!($this->options['tables']['tablespan'] ?? true)) {
            return $block;
        }

        $headerElements      = &$block['element']['elements'][0]['elements'][0]['elements'];
        $headerElementsCount = \count($headerElements);

        for ($index = $headerElementsCount - 1; $index >= 0; --$index) {
            $colspan       = 1;
            $headerElement = &$headerElements[$index];

            while ($index && $headerElements[$index - 1]['handler']['argument'] === '>') {
                ++$colspan;
                $previousHeaderElement           = &$headerElements[--$index];
                $previousHeaderElement['merged'] = true;

                if (isset($previousHeaderElement['attributes'])) {
                    $headerElement['attributes'] = $previousHeaderElement['attributes'];
                }
            }

            if ($colspan > 1) {
                if (!isset($headerElement['attributes'])) {
                    $headerElement['attributes'] = [];
                }

                $headerElement['attributes']['colspan'] = $colspan;
            }
        }

        for ($index = 0; $index < $headerElementsCount; ++$index) {
            if (isset($headerElements[$index]['merged'])) {
                unset($headerElements[$index]);
            }
        }

        $headerElements = \array_values($headerElements);

        $rows = &$block['element']['elements'][1]['elements'];

        foreach ($rows as $rowNo => &$row) {
            $elements = &$row['elements'];

            for ($index = \count($elements) - 1; $index >= 0; --$index) {
                $colspan = 1;
                $element = &$elements[$index];

                while ($index && $elements[$index - 1]['handler']['argument'] === '>') {
                    ++$colspan;
                    $PreviousElement           = &$elements[--$index];
                    $PreviousElement['merged'] = true;
                    if (isset($PreviousElement['attributes'])) {
                        $element['attributes'] = $PreviousElement['attributes'];
                    }
                }

                if ($colspan > 1) {
                    if (!isset($element['attributes'])) {
                        $element['attributes'] = [];
                    }

                    $element['attributes']['colspan'] = $colspan;
                }
            }
        }

        $rowCount = \count($rows);

        foreach ($rows as $rowNo => &$row) {
            $elements = &$row['elements'];

            foreach ($elements as $index => &$element) {
                $rowspan = 1;

                if (isset($element['merged'])) {
                    continue;
                }

                while ($rowNo + $rowspan < $rowCount
                    && $index < \count($rows[$rowNo + $rowspan]['elements'])
                    && $rows[$rowNo + $rowspan]['elements'][$index]['handler']['argument'] === '^'
                    && ($element['attributes']['colspan'] ?? null) === ($rows[$rowNo + $rowspan]['elements'][$index]['attributes']['colspan'] ?? null)
                ) {
                    $rows[$rowNo + $rowspan]['elements'][$index]['merged'] = true;
                    ++$rowspan;
                }

                if ($rowspan > 1) {
                    if (!isset($element['attributes'])) {
                        $element['attributes'] = [];
                    }

                    $element['attributes']['rowspan'] = $rowspan;
                }
            }
        }

        foreach ($rows as $rowNo => &$row) {
            $elements = &$row['elements'];

            for ($index = \count($elements) - 1; $index >= 0; --$index) {
                if (isset($elements[$index]['merged'])) {
                    unset($elements[$index]);
                }
            }

            $row['elements'] = \array_values($elements);
        }

        return $block;
    }

    /**
     * Handle block checkbox
     *
     * @param array{body:string, indent:int, text:string} $line Line data
     * @param null|array                                  $_    Current block (unused parameter)
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockCheckbox(array $line, ?array $_ = null) : ?array
    {
        $text      = \trim($line['text']);
        $beginLine = \substr($text, 0, 4);

        if ($beginLine === '[ ] ') {
            return [
                'handler' => 'unchecked',
                'text'    => \substr(\trim($text), 4),
            ];
        } elseif ($beginLine === '[x] ') {
            return [
                'handler' => 'checked',
                'text'    => \substr(\trim($text), 4),
            ];
        }

        return null;
    }

    /**
     * Continue checkbox.
     *
     * This function doesn't do anything!
     * However required as per the parsing workflow since it is automatically called.
     *
     * @param array{body:string, indent:int, text:string} $_  Line data
     * @param array                                       $__ Current block
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockCheckboxContinue(array $_, array $__) : ?array
    {
        return null;
    }

    /**
     * Complete block checkbox
     *
     * @param array $block Current block
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function blockCheckboxComplete(array $block) : array
    {
        $text = $block['text'];
        if ($this->markupEscaped || $this->safeMode) {
            $text = \htmlspecialchars($text, \ENT_QUOTES, 'UTF-8');
        }

        $html = $block['handler'] === 'unchecked'
            ? '<input type="checkbox" disabled /> ' . $this->formatOnce($text)
            : '<input type="checkbox" checked disabled /> ' . $this->formatOnce($text);

        $block['element'] = [
            'rawHtml'                => $html,
            'allowRawHtmlInSafeMode' => true,
        ];

        return $block;
    }

    /**
     * Formats text without double escaping
     *
     * @param string $text Text to format
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function formatOnce(string $text) : string
    {
        // backup settings
        $markupEscaped = $this->markupEscaped;
        $safeMode      = $this->safeMode;

        // disable rules to prevent double escaping.
        $this->markupEscaped = false;
        $this->safeMode      = false;

        // format line
        $text = $this->elements($this->lineElements($text));

        // reset old values
        $this->markupEscaped = $markupEscaped;
        $this->safeMode      = $safeMode;

        return $text;
    }

    /**
     * Parse attribute data
     *
     * @param string $attribute Attribute string
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function parseAttributeData(string $attribute) : array
    {
        if (!($this->options['special_attributes'] ?? true)) {
            return [];
        }

        $data       = [];
        $attributes = \preg_split('/[ ]+/', $attribute, - 1, \PREG_SPLIT_NO_EMPTY);
        $classes    = [];

        if ($attributes === false) {
            return [];
        }

        foreach ($attributes as $attribute) {
            if ($attribute[0] === '#') {
                $data['id'] = \substr($attribute, 1);
            } else { // "."
                $classes[] = \substr($attribute, 1);
            }
        }

        if (!empty($classes)) {
            $data['class'] = \implode(' ', $classes);
        }

        return $data;
    }

    /**
     * Encodes the ToC tag to a hashed tag and replace.
     *
     * This is used to avoid parsing user defined ToC tag which includes "_" in
     * their tag such as "[[_toc_]]".
     *
     * @param string $text Tag text to encode
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function encodeToCTagToHash(string $text) : string
    {
        $salt      = \bin2hex(\random_bytes(4));
        $tagOrigin = $this->options['toc']['set_toc_tag'] ?? '[toc]';

        if (\strpos($text, $tagOrigin) === false) {
            return $text;
        }

        $tagHashed = \hash('sha256', $salt . $tagOrigin);

        return \str_replace($tagOrigin, $tagHashed, $text);
    }

    /**
     * Decodes the hashed ToC tag to an original tag and replaces.
     *
     * This is used to avoid parsing user defined ToC tag which includes "_" in
     * their tag such as "[[_toc_]]".
     *
     * @param string $text Tag text to encode
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function decodeToCTagFromHash(string $text) : string
    {
        $salt      = \bin2hex(\random_bytes(4));
        $tagOrigin = $this->options['toc']['set_toc_tag'] ?? '[toc]';
        $tagHashed = \hash('sha256', $salt . $tagOrigin);

        if (\strpos($text, $tagHashed) === false) {
            return $text;
        }

        return \str_replace($tagHashed, $tagOrigin, $text);
    }

    /**
     * Generates an anchor text that are link-able even if the heading is not in ASCII.
     *
     * @param string $str Header text
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function createAnchorID(string $str) : string
    {
        if ($this->options['toc']['urlencode'] ?? false) {
            $str = $this->incrementAnchorId($str);

            return \urlencode($str);
        }

        $charMap = [
            // Latin
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'AA', 'Æ' => 'AE', 'Ç' => 'C',
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
            'Ø' => 'OE', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
            'ß' => 'ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'aa', 'æ' => 'ae', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
            'ø' => 'oe', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
            'ÿ' => 'y',

            // Latin symbols
            '©' => '(c)', '®' => '(r)', '™' => '(tm)',

            // Greek
            'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
            'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
            'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
            'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
            'Ϋ' => 'Y',
            'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
            'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
            'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
            'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
            'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',

            // Turkish
            'Ş' => 'S', 'İ' => 'I', 'Ğ' => 'G',
            'ş' => 's', 'ı' => 'i', 'ğ' => 'g',

            // Russian
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
            'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
            'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
            'Я' => 'Ya',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
            'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
            'я' => 'ya',

            // Ukrainian
            'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
            'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',

            // Czech
            'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
            'Ž' => 'Z',
            'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
            'ž' => 'z',

            // Polish
            'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ś' => 'S', 'Ź' => 'Z',
            'Ż' => 'Z',
            'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ś' => 's', 'ź' => 'z',
            'ż' => 'z',

            // Latvian
            'Ā' => 'A', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N', 'Ū' => 'u',
            'ā' => 'a', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n', 'ū' => 'u',
        ];

        // Transliterate characters to ASCII
        if ($this->options['toc']['transliterate'] ?? false) {
            $str = \str_replace(\array_keys($charMap), $charMap, $str);
        }

        // Replace non-alphanumeric characters with our delimiter
        $optionDelimiter = $this->options['toc']['delimiter'] ?? '-';
        $str             = \preg_replace('/[^\p{L}\p{Nd}]+/u', $optionDelimiter, $str) ?? '';

        // Remove duplicate delimiters
        $str = \preg_replace('/(' . \preg_quote($optionDelimiter, '/') . '){2,}/', '$1', $str) ?? '';

        // Truncate slug to max. characters
        $optionLimit = $this->options['toc']['limit'] ?? \mb_strlen($str, 'UTF-8');
        $str         = \mb_substr($str, 0, $optionLimit, 'UTF-8');

        // Remove delimiter from ends
        $str = \trim($str, $optionDelimiter);

        $urlLowercase = $this->options['toc']['lowercase'] ?? true;
        $str          = $urlLowercase ? \mb_strtolower($str, 'UTF-8') : $str;

        return $this->incrementAnchorId($str);
    }

    /**
     * Set/stores the heading block to ToC list in a string and array format.
     *
     * @param array $content ToC content
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected function setContentsList(array $content) : void
    {
        $this->contentsListArray[] = $content;

        $text  = \trim(\strip_tags($this->elements($this->lineElements($content['text']))));
        $id    = $content['id'];
        $level = (int) \trim($content['level'], 'h');

        if ($this->firstHeadLevel === 0) {
            $this->firstHeadLevel = $level;
        }

        // Stores in markdown list format as below:
        // - [Header1](#Header1)
        //   - [Header2-1](#Header2-1)
        //     - [Header3](#Header3)
        //   - [Header2-2](#Header2-2)
        // ...
        $this->contentsListString .= \str_repeat(
            '  ',
            $this->firstHeadLevel - 1 > $level
                ? 1
                : $level - ($this->firstHeadLevel - 1)
        ) . ' - [' . $text . '](#' . $id . ")\n";
    }

    /**
     * Collect and count anchors in use to prevent duplicated ids.
     *
     * Also init optional blacklist of ids.
     *
     * @param string $str Header anchor
     *
     * @return string Incremental, numeric suffix
     *
     * @since 1.0.0
     */
    protected function incrementAnchorId(string $str) : string
    {
        // add blacklist to list of used anchors
        if (!$this->isBlacklistInitialized) {
            $this->initBlacklist();
        }

        do {
            $this->anchorDuplicates[$str] = isset($this->anchorDuplicates[$str]) ? ++$this->anchorDuplicates[$str] : 0;

            $newStr = $str;

            if (($count = $this->anchorDuplicates[$str]) === 0) {
                return $newStr;
            }

            $newStr .= '-' . $count;
        } while (isset($this->anchorDuplicates[$newStr]));

        $this->anchorDuplicates[$newStr] = 0;

        return $newStr;
    }

    /**
     * Add blacklisted ids to anchor list.
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected function initBlacklist() : void
    {
        if ($this->isBlacklistInitialized) {
            return;
        }

        if (!empty($this->options['headings']['blacklist']) && \is_array($this->options['headings']['blacklist'])) {
            foreach ($this->options['headings']['blacklist'] as $v) {
                $this->anchorDuplicates[(string) $v] = 0;
            }
        }

        $this->isBlacklistInitialized = true;
    }

    /**
     * Parse inline elements
     *
     * @param string $text         Text to parse
     * @param array  $nonNestables Inline elements that are not allowed to be nested
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function lineElements(string $text, array $nonNestables = []) : array
    {
        $elements = [];

        if (!empty($nonNestables)) {
            $nonNestables = \array_combine($nonNestables, $nonNestables);
        }

        // $exc is based on the first occurrence of a marker
        while (($exc = \strpbrk($text, $this->inlineMarkerList)) !== false) {
            $marker         = $exc[0];
            $markerPosition = \strlen($text) - \strlen($exc);

            // Get the first char before the marker
            $beforeMarkerPosition = $markerPosition - 1;
            $charBeforeMarker     = $beforeMarkerPosition >= 0 ? $text[$markerPosition - 1] : '';

            $excerpt = ['text' => $exc, 'context' => $text, 'before' => $charBeforeMarker];

            foreach ($this->inlineTypes[$marker] as $inlineType) {
                // check to see if the current inline type is nestable in the current context
                if (isset($nonNestables[$inlineType])) {
                    continue;
                }

                $inline = $this->{"inline{$inlineType}"}($excerpt);
                if ($inline === null) {
                    continue;
                }

                // makes sure that the inline belongs to "our" marker
                if (isset($inline['position']) && $inline['position'] > $markerPosition) {
                    continue;
                }

                // sets a default inline position
                if (!isset($inline['position'])) {
                    $inline['position'] = $markerPosition;
                }

                // cause the new element to 'inherit' our non nestables
                $inline['element']['nonNestables'] = isset($inline['element']['nonNestables'])
                    ? \array_merge($inline['element']['nonNestables'], $nonNestables)
                    : $nonNestables;

                // the text that comes before the inline
                $unmarkedText = \substr($text, 0, $inline['position']);

                // compile the unmarked text
                $inlineText = $this->inlineText($unmarkedText);
                $elements[] = $inlineText['element'];

                // compile the inline
                $elements[] = $this->extractElement($inline);

                // remove the examined text
                $text = \substr($text, $inline['position'] + $inline['extent']);

                continue 2;
            }

            // the marker does not belong to an inline
            $unmarkedText = \substr($text, 0, $markerPosition + 1);

            $inlineText = $this->inlineText($unmarkedText);
            $elements[] = $inlineText['element'];

            $text = \substr($text, $markerPosition + 1);
        }

        $inlineText = $this->inlineText($text);
        $elements[] = $inlineText['element'];

        foreach ($elements as &$element) {
            if (!isset($element['autobreak'])) {
                $element['autobreak'] = false;
            }
        }

        return $elements;
    }

    /**
     * Continue block footnote
     *
     * @param array{body:string, indent:int, text:string} $line  Line data
     * @param array                                       $block Current block
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockFootnoteContinue(array $line, array $block) : ?array
    {
        if ($line['text'][0] === '['
            && \preg_match('/^\[\^(.+?)\]:/', $line['text'])
        ) {
            return null;
        }

        if (isset($block['interrupted'])) {
            if ($line['indent'] >= 4) {
                $block['text'] .= "\n\n" . $line['text'];

                return $block;
            }
        } else {
            $block['text'] .= "\n" . $line['text'];

            return $block;
        }

        return null;
    }

    /**
     * Complete block footnote
     *
     * @param array $block Current block
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function blockFootnoteComplete(array $block) : array
    {
        $this->definitionData['Footnote'][$block['label']] = [
            'text'   => $block['text'],
            'count'  => null,
            'number' => null,
        ];

        return $block;
    }

    /**
     * Continue block footnote
     *
     * @param array{body:string, indent:int, text:string} $line  Line data
     * @param array                                       $block Current block
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockDefinitionListContinue(array $line, array $block) : ?array
    {
        if ($line['text'][0] === ':') {
            return $this->addDdElement($line, $block);
        }

        if (isset($block['interrupted']) && $line['indent'] === 0) {
            return null;
        }

        if (isset($block['interrupted'])) {
            $block['dd']['handler']['function'] = 'textElements';
            $block['dd']['handler']['argument'] .= "\n\n";

            $block['dd']['handler']['destination'] = 'elements';

            unset($block['interrupted']);
        }

        $text = \substr($line['body'], \min($line['indent'], 4));

        $block['dd']['handler']['argument'] .= "\n" . $text;

        return $block;
    }

    /**
     * Continue block markup
     *
     * @param array{body:string, indent:int, text:string} $line  Line data
     * @param array                                       $block Current block
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockMarkupContinue(array $line, array $block) : ?array
    {
        if (isset($block['closed'])) {
            return null;
        }

        if (\preg_match('/^<' . $block['name'] . '(?:[ ]*' . $this->regexHtmlAttribute . ')*[ ]*>/i', $line['text'])) {
            // open
            ++$block['depth'];
        }

        if (\preg_match('/(.*?)<\/' . $block['name'] . '>[ ]*$/i', $line['text'], $matches)) {
            // close
            if ($block['depth'] > 0) {
                --$block['depth'];
            } else {
                $block['closed'] = true;
            }
        }

        if (isset($block['interrupted'])) {
            $block['element']['rawHtml'] .= "\n";
            unset($block['interrupted']);
        }

        $block['element']['rawHtml'] .= "\n".$line['body'];

        return $block;
    }

    /**
     * Complete block markup
     *
     * @param array $block Current block
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function blockMarkupComplete(array $block) : array
    {
        if (!isset($block['void'])) {
            $block['element']['rawHtml'] = $this->processTag($block['element']['rawHtml']);
        }

        return $block;
    }

    /**
     * Handle footnote marker
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineFootnoteMarker(array $excerpt) : ?array
    {
        if (\preg_match('/^\[\^(.+?)\]/', $excerpt['text'], $matches) !== 1) {
            return null;
        }

        $name = $matches[1];

        if (!isset($this->definitionData['Footnote'][$name])) {
            return null;
        }

        ++$this->definitionData['Footnote'][$name]['count'];

        if (!isset($this->definitionData['Footnote'][$name]['number'])) {
            $this->definitionData['Footnote'][$name]['number'] = ++ $this->footnoteCount; // » &
        }

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'name'       => 'sup',
                'attributes' => ['id' => 'fnref' . $this->definitionData['Footnote'][$name]['count'] . ':' . $name],
                'element'    => [
                    'name'       => 'a',
                    'attributes' => ['href' => '#fn:' . $name, 'class' => 'footnote-ref'],
                    'text'       => $this->definitionData['Footnote'][$name]['number'],
                ],
            ],
        ];
    }

    /**
     * Insert/replace text with abbreviation
     *
     * @param array $element Element to insert abbreviation into
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function insertAbreviation(array $element) : array
    {
        if (!isset($element['text'])) {
            return $element;
        }

        $element['elements'] = self::pregReplaceElements(
            '/\b' . \preg_quote($this->currentAbreviation, '/') . '\b/',
            [
                [
                    'name'       => 'abbr',
                    'attributes' => [
                        'title' => $this->currentMeaning,
                    ],
                    'text' => $this->currentAbreviation,
                ],
            ],
            $element['text']
        );

        unset($element['text']);

        return $element;
    }

    /**
     * Inline elements in text
     *
     * @param string $text Text to search for inlinable elements
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function inlineText(string $text) : array
    {
        $inline = [
            'extent'  => \strlen($text),
            'element' => [],
        ];

        $inline['element']['elements'] = self::pregReplaceElements(
            $this->breaksEnabled ? '/[ ]*+\n/' : '/(?:[ ]*+\\\\|[ ]{2,}+)\n/',
            [
                ['name' => 'br'],
                ['text' => "\n"],
            ],
            $text
        );

        // Handle abbreviations
        if (!isset($this->definitionData['Abbreviation'])) {
            return $inline;
        }

        foreach ($this->definitionData['Abbreviation'] as $abbreviation => $meaning) {
            $this->currentAbreviation = $abbreviation;
            $this->currentMeaning     = $meaning;

            $inline['element'] = $this->elementApplyRecursiveDepthFirst(
                'insertAbreviation',
                $inline['element']
            );
        }

        return $inline;
    }

    /**
     * Handle block list
     *
     * @param array $line  Line data
     * @param array $block Current block
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function addDdElement(array $line, array $block) : array
    {
        $text = \substr($line['text'], 1);
        $text = \trim($text);

        unset($block['dd']);

        $block['dd'] = [
            'name'    => 'dd',
            'handler' => [
                'function'    => 'lineElements',
                'argument'    => $text,
                'destination' => 'elements',
            ],
        ];

        if (isset($block['interrupted'])) {
            $block['dd']['handler']['function'] = 'textElements';

            unset($block['interrupted']);
        }

        $block['element']['elements'][] = &$block['dd'];

        return $block;
    }

    /**
     * Create footnotes
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function buildFootnoteElement() : array
    {
        $element = [
            'name'       => 'div',
            'attributes' => ['class' => 'footnotes'],
            'elements'   => [
                ['name' => 'hr'],
                [
                    'name'     => 'ol',
                    'elements' => [],
                ],
            ],
        ];

        \uasort($this->definitionData['Footnote'], [self::class, 'sortFootnotes']);

        foreach ($this->definitionData['Footnote'] as $definitionId => $definitionData) {
            if (!isset($definitionData['number'])) {
                continue;
            }

            $text             = $definitionData['text'];
            $textElements     = $this->textElements($text);
            $numbers          = \range(1, $definitionData['count']);
            $backLinkElements = [];

            foreach ($numbers as $number) {
                $backLinkElements[] = ['text' => ' '];
                $backLinkElements[] = [
                    'name'       => 'a',
                    'attributes' => [
                        'href'  => "#fnref{$number}:{$definitionId}",
                        'rev'   => 'footnote',
                        'class' => 'footnote-backref',
                    ],
                    'rawHtml'                => '&#8617;',
                    'allowRawHtmlInSafeMode' => true,
                    'autobreak'              => false,
                ];
            }

            unset($backLinkElements[0]);

            $n = \count($textElements) - 1;

            if ($textElements[$n]['name'] === 'p') {
                $backLinkElements = \array_merge(
                    [
                        [
                            'rawHtml'                => '&#160;',
                            'allowRawHtmlInSafeMode' => true,
                        ],
                    ],
                    $backLinkElements
                );

                unset($textElements[$n]['name']);

                $textElements[$n] = [
                    'name'     => 'p',
                    'elements' => \array_merge(
                        [$textElements[$n]],
                        $backLinkElements
                    ),
                ];
            } else {
                $textElements[] = [
                    'name'     => 'p',
                    'elements' => $backLinkElements,
                ];
            }

            $element['elements'][1]['elements'][] = [
                'name'       => 'li',
                'attributes' => ['id' => 'fn:' . $definitionId],
                'elements'   => \array_merge(
                    $textElements
                ),
            ];
        }

        return $element;
    }

    /**
     * Handle markup/html.
     *
     * Ensures that html is well formed.
     *
     * This function is called recursively
     *
     * @param string $elementMarkup Markup
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function processTag(string $elementMarkup) : string
    {
        // http://stackoverflow.com/q/1148928/200145
        \libxml_use_internal_errors(true);

        $dom = new \DOMDocument();

        // http://stackoverflow.com/q/11309194/200145
        $elementMarkup = \mb_convert_encoding($elementMarkup, 'HTML-ENTITIES', 'UTF-8');

        // http://stackoverflow.com/q/4879946/200145
        $dom->loadHTML($elementMarkup);

        if ($dom->documentElement === null) {
            return '';
        }

        if ($dom->doctype !== null) {
            $dom->removeChild($dom->doctype);
        }

        if ($dom->firstChild !== null && $dom->firstChild->firstChild?->firstChild !== null) {
            $dom->replaceChild($dom->firstChild->firstChild->firstChild, $dom->firstChild);
        }

        $elementText = '';

        if ($dom->documentElement->getAttribute('markdown') === '1') {
            foreach ($dom->documentElement->childNodes as $node) {
                $elementText .= $dom->saveHTML($node);
            }

            $dom->documentElement->removeAttribute('markdown');

            $elementText = "\n" . $this->text($elementText) . "\n";
        } else {
            foreach ($dom->documentElement->childNodes as $node) {
                $nodeMarkup = $dom->saveHTML($node);
                if ($nodeMarkup === false) {
                    $nodeMarkup = '';
                }

                $elementText .= $node instanceof \DOMElement && !\in_array($node->nodeName, $this->textLevelElements)
                    ? $this->processTag($nodeMarkup)
                    : $nodeMarkup;
            }
        }

        // because we don't want for markup to get encoded
        $dom->documentElement->nodeValue = 'placeholder\x1A';

        $markup = $dom->saveHTML($dom->documentElement);
        if ($markup === false) {
            return '';
        }

        return \str_replace('placeholder\x1A', $elementText, $markup);
    }

    /**
     * Footnote sort function
     *
     * @param array $a First element
     * @param array $b Second element
     *
     * @return int
     *
     * @since 1.0.0
     */
    protected function sortFootnotes(array $a, array $b) : int
    {
        return $a['number'] <=> $b['number'];
    }

    /**
     * Parse text elements to lines and then handle lines.
     *
     * @param string $text Text to parse
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function textElements(string $text) : array
    {
        // make sure no definitions are set
        $this->definitionData = [];

        // standardize line breaks
        $text = \str_replace(["\r\n", "\r"], "\n", $text);

        // remove surrounding line breaks
        $text = \trim($text, "\n");

        // split text into lines
        $lines = \explode("\n", $text);

        // iterate through lines to identify blocks
        return $this->linesElements($lines);
    }

    /**
     * Handle lines of elements
     *
     * @param string[] $lines Lines to parse
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function linesElements(array $lines) : array
    {
        $elements     = [];
        $currentBlock = null;

        foreach ($lines as $line) {
            if (\rtrim($line) === '') {
                if (isset($currentBlock)) {
                    $currentBlock['interrupted'] = (isset($currentBlock['interrupted'])
                        ? $currentBlock['interrupted'] + 1 : 1
                    );
                }

                continue;
            }

            while (($beforeTab = \strstr($line, "\t", true)) !== false) {
                $shortage = 4 - \mb_strlen($beforeTab, 'utf-8') % 4;

                $line = $beforeTab
                    . \str_repeat(' ', $shortage)
                    . \substr($line, \strlen($beforeTab) + 1);
            }

            $indent = \strspn($line, ' ');
            $text   = $indent > 0 ? \substr($line, $indent) : $line;
            $line   = ['body' => $line, 'indent' => $indent, 'text' => $text];

            if (isset($currentBlock['continuable'])) {
                $methodName = 'block' . $currentBlock['type'] . 'Continue';
                $block      = $this->{$methodName}($line, $currentBlock);

                if (isset($block)) {
                    $currentBlock = $block;
                    continue;
                } elseif (\in_array($currentBlock['type'], self::COMPLETABLE)) {
                    $methodName   = 'block' . $currentBlock['type'] . 'Complete';
                    $currentBlock = $this->{$methodName}($currentBlock);
                }
            }

            $marker     = $text[0];
            $blockTypes = $this->unmarkedBlockTypes;

            if (isset($this->blockTypes[$marker])) {
                foreach ($this->blockTypes[$marker] as $blockType) {
                    $blockTypes [] = $blockType;
                }
            }

            foreach ($blockTypes as $blockType) {
                $block = $this->{"block{$blockType}"}($line, $currentBlock);

                if (isset($block)) {
                    $block['type'] = $blockType;

                    if (!isset($block['identified'])) {
                        if (isset($currentBlock)) {
                            $elements[] = $this->extractElement($currentBlock);
                        }

                        $block['identified'] = true;
                    }

                    if (\in_array($blockType, self::CONTINUABLE)) {
                        $block['continuable'] = true;
                    }

                    $currentBlock = $block;

                    continue 2;
                }
            }

            if (isset($currentBlock) && $currentBlock['type'] === 'Paragraph') {
                $block = $this->paragraphContinue($line, $currentBlock);
            }

            if (isset($block)) {
                $currentBlock = $block;
            } else {
                if (isset($currentBlock)) {
                    $elements[] = $this->extractElement($currentBlock);
                }

                $currentBlock = [
                    'type'    => 'Paragraph',
                    'element' => [
                        'name'    => 'p',
                        'handler' => [
                            'function'    => 'lineElements',
                            'argument'    => $line['text'],
                            'destination' => 'elements',
                        ],
                    ],
                ];

                $currentBlock['identified'] = true;
            }
        }

        if (isset($currentBlock['continuable']) && \in_array($currentBlock['type'], self::COMPLETABLE)) {
            $methodName   = 'block' . $currentBlock['type'] . 'Complete';
            $currentBlock = $this->{$methodName}($currentBlock);
        }

        if (isset($currentBlock)) {
            $elements[] = $this->extractElement($currentBlock);
        }

        return $elements;
    }

    /**
     * Extract element from block
     *
     * @param array $block Block
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function extractElement(array $block) : array
    {
        if (!isset($block['element'])) {
            if (isset($block['markup'])) {
                $block['element'] = ['rawHtml' => $block['markup']];
            } elseif (isset($block['hidden'])) {
                $block['element'] = [];
            }
        }

        return $block['element'];
    }

    /**
     * Continue block code
     *
     * @param array{body:string, indent:int, text:string} $line  Line data
     * @param array                                       $block Current block
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockCodeContinue(array $line, array $block) : ?array
    {
        if ($line['indent'] < 4) {
            return null;
        }

        if (isset($block['interrupted'])) {
            $block['element']['element']['text'] .= \str_repeat("\n", $block['interrupted']);

            unset($block['interrupted']);
        }

        $block['element']['element']['text'] .= "\n";

        $text = \substr($line['body'], 4);

        $block['element']['element']['text'] .= $text;

        return $block;
    }

    /**
     * Complete block code
     *
     * @param array $block Current block
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function blockCodeComplete(array $block) : array
    {
        return $block;
    }

    /**
     * Continue block code
     *
     * @param array{body:string, indent:int, text:string} $line  Line data
     * @param array                                       $block Current block
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockCommentContinue(array $line, array $block) : ?array
    {
        if (isset($block['closed'])) {
            return null;
        }

        $block['element']['rawHtml'] .= "\n" . $line['body'];

        if (\strpos($line['text'], '-->') !== false) {
            $block['closed'] = true;
        }

        return $block;
    }

    /**
     * Continue block fenced code
     *
     * @param array{body:string, indent:int, text:string} $line  Line data
     * @param array                                       $block Current block
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockFencedCodeContinue(array $line, array $block) : ?array
    {
        if (isset($block['complete'])) {
            return null;
        }

        if (isset($block['interrupted'])) {
            $block['element']['element']['text'] .= \str_repeat("\n", $block['interrupted']);

            unset($block['interrupted']);
        }

        if (($len = \strspn($line['text'], $block['char'])) >= $block['openerLength']
            && \rtrim(\substr($line['text'], $len), ' ') === ''
        ) {
            $block['element']['element']['text'] = \substr($block['element']['element']['text'], 1);

            $block['complete'] = true;

            return $block;
        }

        $block['element']['element']['text'] .= "\n" . $line['body'];

        return $block;
    }

    /**
     * Complete block fenced code
     *
     * @param array $block Current block
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function blockFencedCodeComplete(array $block) : array
    {
        return $block;
    }

    /**
     * Continue block spoiler
     *
     * @param array{body:string, indent:int, text:string} $line  Line data
     * @param array                                       $block Current block
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockSpoilerContinue(array $line, array $block) : ?array
    {
        if (isset($block['complete'])) {
            return null;
        }

        if (isset($block['interrupted'])) {
            $block['element']['element']['text'] .= \str_repeat("\n", $block['interrupted']);

            unset($block['interrupted']);
        }

        if (($len = \strspn($line['text'], $block['char'])) >= $block['openerLength']
            && \rtrim(\substr($line['text'], $len), ' ') === ''
        ) {
            $block['element']['element']['text'] = \substr($block['element']['element']['text'], 1);

            $block['complete'] = true;

            return $block;
        }

        $block['element']['element']['elements'][1]['text'] .= "\n" . $line['body'];

        return $block;
    }

    /**
     * Complete block spoiler
     *
     * @param array $block Current block
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function blockSpoilerComplete(array $block) : array
    {
        return $block;
    }

    /**
     * Continue block list
     *
     * @param array{body:string, indent:int, text:string} $line  Line data
     * @param array                                       $block Current block
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockListContinue(array $line, array $block) : ?array
    {
        if (isset($block['interrupted']) && empty($block['li']['handler']['argument'])) {
            return null;
        }

        $requiredIndent = $block['indent'] + \strlen($block['data']['marker']);

        if ($line['indent'] < $requiredIndent
            && (($block['data']['type'] === 'ol'
                && \preg_match('/^[0-9]++' . $block['data']['markerTypeRegex'] . '(?:[ ]++(.*)|$)/', $line['text'], $matches)
            ) || ($block['data']['type'] === 'ul'
                && \preg_match('/^' . $block['data']['markerTypeRegex'] . '(?:[ ]++(.*)|$)/', $line['text'], $matches)
            ))
        ) {
            if (isset($block['interrupted'])) {
                $block['li']['handler']['argument'][] = '';

                $block['loose'] = true;

                unset($block['interrupted']);
            }

            unset($block['li']);

            $text = $matches[1] ?? '';

            $block['indent'] = $line['indent'];

            $block['li'] = [
                'name'    => 'li',
                'handler' => [
                    'function'    => 'li',
                    'argument'    => [$text],
                    'destination' => 'elements',
                ],
            ];

            $block['element']['elements'][] = &$block['li'];

            return $block;
        } elseif ($line['indent'] < $requiredIndent && $this->blockList($line)) {
            return null;
        }

        if ($line['text'][0] === '[' && $this->blockReference($line)) {
            return $block;
        }

        if ($line['indent'] >= $requiredIndent) {
            if (isset($block['interrupted'])) {
                $block['li']['handler']['argument'][] = '';

                $block['loose'] = true;

                unset($block['interrupted']);
            }

            $text = \substr($line['body'], $requiredIndent);

            $block['li']['handler']['argument'][] = $text;

            return $block;
        }

        if (!isset($block['interrupted'])) {
            $text = \preg_replace('/^[ ]{0,' . $requiredIndent . '}+/', '', $line['body']);

            $block['li']['handler']['argument'][] = $text;

            return $block;
        }

        return null;
    }

    /**
     * Complete block list
     *
     * @param array $block Current block
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function blockListComplete(array $block) : array
    {
        if (!isset($block['loose'])) {
            return $block;
        }

        foreach ($block['element']['elements'] as &$li) {
            if (\end($li['handler']['argument']) !== '') {
                $li['handler']['argument'][] = '';
            }
        }

        return $block;
    }

    /**
     * Continue block quote
     *
     * @param array{body:string, indent:int, text:string} $line  Line data
     * @param array                                       $block Current block
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockQuoteContinue(array $line, array $block) : ?array
    {
        if (isset($block['interrupted'])) {
            return null;
        }

        if ($line['text'][0] === '>' && \preg_match('/^>[ ]?+(.*+)/', $line['text'], $matches)) {
            $block['element']['handler']['argument'][] = $matches[1];

            return $block;
        }

        if (!isset($block['interrupted'])) {
            $block['element']['handler']['argument'][] = $line['text'];

            return $block;
        }

        return null;
    }

    /**
     * Continue block table
     *
     * @param array{body:string, indent:int, text:string} $line  Line data
     * @param array                                       $block Current block
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockTableContinue(array $line, array $block) : ?array
    {
        if (isset($block['interrupted'])
            || (\count($block['alignments']) !== 1 && $line['text'][0] !== '|' && \strpos($line['text'], '|') === false)
        ) {
            return null;
        }

        $elements = [];

        $row = $line['text'];

        $row = \trim($row);
        $row = \trim($row, '|');

        \preg_match_all('/(?:(\\\\[|])|[^|`]|`[^`]++`|`)++/', $row, $matches);

        $cells = \array_slice($matches[0], 0, \count($block['alignments']));

        foreach ($cells as $index => $cell) {
            $cell = \trim($cell);

            $element = [
                'name'    => 'td',
                'handler' => [
                    'function'    => 'lineElements',
                    'argument'    => $cell,
                    'destination' => 'elements',
                ],
            ];

            if (isset($block['alignments'][$index])) {
                $element['attributes'] = [
                    'style' => 'text-align: ' . $block['alignments'][$index] . ';',
                ];
            }

            $elements [] = $element;
        }

        $element = [
            'name'     => 'tr',
            'elements' => $elements,
        ];

        $block['element']['elements'][1]['elements'][] = $element;

        return $block;
    }

    /**
     * Continue block paragraph
     *
     * @param array{body:string, indent:int, text:string} $line  Line data
     * @param array                                       $block Current block
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function paragraphContinue(array $line, array $block) : ?array
    {
        if (isset($block['interrupted'])) {
            return null;
        }

        $block['element']['handler']['argument'] .= "\n".$line['text'];

        return $block;
    }

    /**
     * Handle link
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function inlineLinkParent(array $excerpt) : ?array
    {
        $element = [
            'name'    => 'a',
            'handler' => [
                'function'    => 'lineElements',
                'argument'    => null,
                'destination' => 'elements',
            ],
            'nonNestables' => ['Url', 'Link'],
            'attributes'   => [
                'href'  => null,
                'title' => null,
            ],
        ];

        $extent    = 0;
        $remainder = $excerpt['text'];

        if (\preg_match('/\[((?:[^][]++|(?R))*+)\]/', $remainder, $matches)) {
            $element['handler']['argument'] = $matches[1];

            $extent += \strlen($matches[0]);

            $remainder = \substr($remainder, $extent);
        } else {
            return null;
        }

        if (\preg_match('/^[(]\s*+((?:[^ ()]++|[(][^ )]+[)])++)(?:[ ]+("[^"]*+"|\'[^\']*+\'))?\s*+[)]/', $remainder, $matches)) {
            $element['attributes']['href'] = UriFactory::build($matches[1]);

            if (isset($matches[2])) {
                $element['attributes']['title'] = \substr($matches[2], 1, - 1);
            }

            $extent += \strlen($matches[0]);
        } else {
            if (\preg_match('/^\s*\[(.*?)\]/', $remainder, $matches)) {
                $definition = \strlen($matches[1]) !== 0 ? $matches[1] : $element['handler']['argument'];
                $definition = \strtolower($definition);

                $extent += \strlen($matches[0]);
            } else {
                $definition = \strtolower($element['handler']['argument']);
            }

            if (!isset($this->definitionData['Reference'][$definition])) {
                return null;
            }

            $definition = $this->definitionData['Reference'][$definition];

            $element['attributes']['href']  = $definition['url'];
            $element['attributes']['title'] = $definition['title'];
        }

        return [
            'extent'  => $extent,
            'element' => $element,
        ];
    }

    /**
     * Handle special character
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function inlineSpecialCharacter(array $excerpt) : ?array
    {
        if (\substr($excerpt['text'], 1, 1) !== ' ' && \strpos($excerpt['text'], ';') !== false
            && \preg_match('/^&(#?+[0-9a-zA-Z]++);/', $excerpt['text'], $matches)
        ) {
            return [
                'element' => ['rawHtml' => '&' . $matches[1] . ';'],
                'extent'  => \strlen($matches[0]),
            ];
        }

        return null;
    }

    /**
     * Handle "handler"
     *
     * @param array $element Element to handle
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function handle(array $element) : array
    {
        if (!isset($element['handler'])) {
            return $element;
        }

        if (!isset($element['nonNestables'])) {
            $element['nonNestables'] = [];
        }

        if (\is_string($element['handler'])) {
            $function = $element['handler'];
            $argument = $element['text'];
            unset($element['text']);
            $destination = 'rawHtml';
        } else {
            $function    = $element['handler']['function'];
            $argument    = $element['handler']['argument'];
            $destination = $element['handler']['destination'];
        }

        $element[$destination] = $this->{$function}($argument, $element['nonNestables']);

        if ($destination === 'handler') {
            $element = $this->handle($element);
        }

        unset($element['handler']);

        return $element;
    }

    /**
     * Handle element recursively
     *
     * @param string|\Closure $closure Closure for handling element
     * @param array           $element Element to handle
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function elementApplyRecursive(string|\Closure $closure, array $element) : array
    {
        $element = \is_string($closure) ? $this->{$closure}($element) : $closure($element);

        if (isset($element['elements'])) {
            foreach ($element['elements'] as &$e) {
                $e = $this->elementApplyRecursive($closure, $e);
            }
        } elseif (isset($element['element'])) {
            $element['element'] = $this->elementApplyRecursive($closure, $element['element']);
        }

        return $element;
    }

    /**
     * Handle element recursively
     *
     * @param string|\Closure $closure Closure for handling element
     * @param array           $element Element to handle
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function elementApplyRecursiveDepthFirst(string|\Closure $closure, array $element) : array
    {
        if (isset($element['elements'])) {
            foreach ($element['elements'] as &$e) {
                $e = $this->elementApplyRecursiveDepthFirst($closure, $e);
            }
        } elseif (isset($element['element'])) {
            foreach ($element['element'] as &$e) {
                $e = $this->elementApplyRecursiveDepthFirst($closure, $e);
            }
        }

        return \is_string($closure) ? $this->{$closure}($element) : $closure($element);
    }

    /**
     * Render element
     *
     * @param array $element Element to render
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function element(array $element) : string
    {
        if ($this->safeMode) {
            $element = $this->sanitizeElement($element);
        }

        // identity map if element has no handler
        $element = $this->handle($element);
        $hasName = isset($element['name']);
        $markup  = '';

        if ($hasName) {
            $markup .= '<' . $element['name'];

            if (isset($element['attributes'])) {
                foreach ($element['attributes'] as $name => $value) {
                    if ($value === null) {
                        continue;
                    }

                    $markup .= ' ' . $name . '="' . \htmlspecialchars((string) $value, \ENT_QUOTES, 'UTF-8') . '"';
                }
            }
        }

        $permitRawHtml = false;
        $text = null;

        if (isset($element['text'])) {
            $text = $element['text'];
        } elseif (isset($element['rawHtml'])) {
            // very strongly consider an alternative if you're writing an extension
            $text = $element['rawHtml'];

            $permitRawHtml = !$this->safeMode || ($element['allowRawHtmlInSafeMode'] ?? false);
        }

        $hasContent = isset($text) || isset($element['element']) || isset($element['elements']);

        if ($hasContent) {
            $markup .= $hasName ? '>' : '';

            if (isset($element['elements'])) {
                $markup .= $this->elements($element['elements']);
            } elseif (isset($element['element'])) {
                $markup .= $this->element($element['element']);
            } elseif (!$permitRawHtml) {
                $markup .= \htmlspecialchars((string) $text, \ENT_NOQUOTES, 'UTF-8');
            } else {
                $markup .= $text;
            }

            $markup .= $hasName ? '</' . $element['name'] . '>' : '';
        } elseif ($hasName) {
            $markup .= ' />';
        }

        return $markup;
    }

    /**
     * Render elements
     *
     * @param array $elements Elements to render
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function elements(array $elements) : string
    {
        $markup    = '';
        $autoBreak = true;

        foreach ($elements as $element) {
            if (empty($element)) {
                continue;
            }

            $autoBreakNext = (isset($element['autobreak'])
                ? $element['autobreak'] : isset($element['name'])
            );

            // (autobreak === false) covers both sides of an element
            $autoBreak = $autoBreak ? $autoBreakNext : $autoBreak;

            $markup .= ($autoBreak ? "\n" : '') . $this->element($element);
            $autoBreak = $autoBreakNext;
        }

        return $markup . ($autoBreak ? "\n" : '');
    }

    /**
     * Handle list
     *
     * @param string[] $lines Lines
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function li(array $lines) : array
    {
        $elements = $this->linesElements($lines);

        if (!\in_array('', $lines)
            && isset($elements[0], $elements[0]['name'])
            && $elements[0]['name'] === 'p'
        ) {
            unset($elements[0]['name']);
        }

        return $elements;
    }

    /**
     * Replace occurrences $regexp with $elements in $text.
     *
     * @param string $regexp   Regex
     * @param array  $elements Elements to replace
     * @param string $text     Text to match against regex
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected static function pregReplaceElements(string $regexp, array $elements, string $text) : array
    {
        $newElements = [];

        while (\preg_match($regexp, $text, $matches, \PREG_OFFSET_CAPTURE)) {
            $offset = (int) $matches[0][1];
            $before = \substr($text, 0, $offset);
            $after  = \substr($text, $offset + \strlen($matches[0][0]));

            $newElements[] = ['text' => $before];

            foreach ($elements as $element) {
                $newElements[] = $element;
            }

            $text = $after;
        }

        $newElements[] = ['text' => $text];

        return $newElements;
    }

    /**
     * Sanitize element
     *
     * @param array $element Element to sanitize
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function sanitizeElement(array $element) : array
    {
        static $goodAttribute    = '/^[a-zA-Z0-9][a-zA-Z0-9-_]*+$/';
        static $safeUrlNameToAtt = [
            'a'   => 'href',
            'img' => 'src',
        ];

        if (!isset($element['name'])) {
            unset($element['attributes']);

            return $element;
        }

        if (isset($safeUrlNameToAtt[$element['name']])) {
            $element = $this->filterUnsafeUrlInAttribute($element, $safeUrlNameToAtt[$element['name']]);
        }

        if (!empty($element['attributes'])) {
            foreach ($element['attributes'] as $att => $_) {
                if (!\preg_match($goodAttribute, $att)) {
                    // filter out badly parsed attribute
                    unset($element['attributes'][$att]);
                } elseif (\str_starts_with($att, 'on')) {
                    // dump onevent attribute
                    unset($element['attributes'][$att]);
                }
            }
        }

        return $element;
    }

    /**
     * Sanitize url in attribute
     *
     * @param array  $element   Element to sanitize
     * @param string $attribute Attribute to sanitize
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function filterUnsafeUrlInAttribute(array $element, string $attribute) : array
    {
        foreach ($this->safeLinksWhitelist as $scheme) {
            if (\str_starts_with($element['attributes'][$attribute], $scheme)) {
                return $element;
            }
        }

        $element['attributes'][$attribute] = \str_replace(':', '%3A', $element['attributes'][$attribute]);

        return $element;
    }
}
