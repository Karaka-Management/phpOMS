<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package    phpOMS\Utils\Parser\Markdown
 * @license    Original license Emanuil Rusev, erusev.com (MIT)
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
 * @license    Original & extra license Emanuil Rusev, erusev.com (MIT)
 * @license    Extended license Benjamin Hoegh (MIT)
 * @license    This version: OMS License 2.0
 * @link       https://jingga.app
 * @see        https://github.com/erusev/parsedown
 * @see        https://github.com/erusev/parsedown-extra
 * @see        https://github.com/BenjaminHoegh/ParsedownExtended
 * @since      1.0.0
 */
class Markdown
{
    /**
     * Parsedown version
     *
     * @var string
     * @since 1.0.0
     */
    public const version = '1.8.0-beta-7';

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
        '_' => '/^__((?:\\\\_|[^_]|_[^_]*+_)+?)__(?!_)/us',
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
    private string $idToc = '';

    /**
     * TOC array after parsing headers
     *
     * @var array{text:string, id:string, level:string}
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
    // TOC: end

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

    /**
     * Instances
     *
     * @var array<string, self>
     * @since 1.0.0
     */
    private static $instances = [];

    /**
     * Create instance for static use
     *
     * @param string $name Instance name
     *
     * @return self
     *
     * @since 1.0.0
     */
    public static function instance(string $name = 'default') : self
    {
        if (isset(self::$instances[$name])) {
            return self::$instances[$name];
        }

        $instance = new static();

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
        $state = $this->options['mark'] ?? true;
        if ($state !== false) {
            $this->InlineTypes['='][] = 'mark';
            $this->inlineMarkerList  .= '=';
        }

        // Keystrokes
        $state = $this->options['keystrokes'] ?? true;
        if ($state !== false) {
            $this->InlineTypes['['][] = 'Keystrokes';
            $this->inlineMarkerList  .= '[';
        }

        // Inline Math
        $state = $this->options['math'] ?? false;
        if ($state !== false) {
            $this->InlineTypes['\\'][] = 'Math';
            $this->inlineMarkerList   .= '\\';
            $this->InlineTypes['$'][]  = 'Math';
            $this->inlineMarkerList   .= '$';
        }

        // Superscript
        $state = $this->options['sup'] ?? false;
        if ($state !== false) {
            $this->InlineTypes['^'][] = 'Superscript';
            $this->inlineMarkerList  .= '^';
        }

        // Subscript
        $state = $this->options['sub'] ?? false;
        if ($state !== false) {
            $this->InlineTypes['~'][] = 'Subscript';
        }

        // Emojis
        $state = $this->options['emojis'] ?? true;
        if ($state !== false) {
            $this->InlineTypes[':'][] = 'Emojis';
            $this->inlineMarkerList  .= ':';
        }

        // Typographer
        $state = $this->options['typographer'] ?? false;
        if ($state !== false) {
            $this->InlineTypes['('][] = 'Typographer';
            $this->inlineMarkerList  .= '(';
            $this->InlineTypes['.'][] = 'Typographer';
            $this->inlineMarkerList  .= '.';
            $this->InlineTypes['+'][] = 'Typographer';
            $this->inlineMarkerList  .= '+';
            $this->InlineTypes['!'][] = 'Typographer';
            $this->inlineMarkerList  .= '!';
            $this->InlineTypes['?'][] = 'Typographer';
            $this->inlineMarkerList  .= '?';
        }

        // Smartypants
        $state = $this->options['smarty'] ?? false;
        if ($state !== false) {
            $this->InlineTypes['<'][] = 'Smartypants';
            $this->inlineMarkerList  .= '<';
            $this->InlineTypes['>'][] = 'Smartypants';
            $this->inlineMarkerList  .= '>';
            $this->InlineTypes['-'][] = 'Smartypants';
            $this->inlineMarkerList  .= '-';
            $this->InlineTypes['.'][] = 'Smartypants';
            $this->inlineMarkerList  .= '.';
            $this->InlineTypes["'"][] = 'Smartypants';
            $this->inlineMarkerList  .= "'";
            $this->InlineTypes['"'][] = 'Smartypants';
            $this->inlineMarkerList  .= '"';
            $this->InlineTypes['`'][] = 'Smartypants';
            $this->inlineMarkerList  .= '`';
        }

        // Block Math
        $state = $this->options['math'] ?? false;
        if ($state !== false) {
            $this->BlockTypes['\\'][] = 'Math';
            $this->BlockTypes['$'][]  = 'Math';
        }

        // Task
        $state = $this->options['lists']['tasks'] ?? true;
        if ($state !== false) {
            $this->BlockTypes['['][] = 'Checkbox';
        }
    }

    public function textParent($text) : string
    {
        $Elements = $this->textElements($text);
        $markup   = $this->elements($Elements);
        $markup   = \trim($markup, "\n");

        // Merge consecutive dl elements
        $markup = \preg_replace('/<\/dl>\s+<dl>\s+/', '', $markup);

        // Add footnotes
        if (isset($this->definitionData['Footnote'])) {
            $Element = $this->buildFootnoteElement();
            $markup .= "\n" . $this->element($Element);
        }

        return $markup;
    }

    /**
     * Parses the given markdown string to an HTML string but it ignores ToC
     *
     * @param string $text Markdown text to parse
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function body(string $text) : string
    {
        $text = $this->encodeTagToHash($text);  // Escapes ToC tag temporary
        $html = $this->textParent($text);       // Parses the markdown text

        return $this->decodeTagFromHash($html); // Unescape the ToC tag
    }

    /**
     * Parses markdown string to HTML and also the "[toc]" tag as well.
     * It overrides the parent method: \Parsedown::text().
     */
    public function text($text) : string
    {
        // Parses the markdown text except the ToC tag. This also searches
        // the list of contents and available to get from "contentsList()"
        // method.
        $html = $this->body($text);

        if (isset($this->options['toc']) && $this->options['toc'] === false) {
            return $html;
        }

        // Handle toc
        $tagOrigin = $this->getTagToC();

        if (\strpos($text, $tagOrigin) === false) {
            return $html;
        }

        $tocData = $this->contentsList();
        $tocId   = $this->getIdAttributeToC();
        $needle  = '<p>' . $tagOrigin . '</p>';
        $replace = '<div id="' . $tocId . '">' . $tocData . '</div>';

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
        if (\strtolower($typeReturn) === 'html') {
            $result = '';
            if (!empty($this->contentsListString)) {
                // Parses the ToC list in markdown to HTML
                $result = $this->body($this->contentsListString);
            }

            return $result;
        } elseif (\strtolower($typeReturn) === 'json') {
            return \json_encode($this->contentsListArray);
        }

        return $this->contentsList('html');
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
        if (($this->options['code']['inline'] ?? true) !== true
            || ($this->options['code'] ?? true) !== true
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
            || \preg_match('/^<((mailto:)?{' . $commonMarkEmail . '})>/i', $excerpt['text'], $matches) !== 1
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
            || !isset($excerpt['text'][1]) || $excerpt['text'][1] !== '['
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
     * Handle striketrhough
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
            || !isset($excerpt['text'][1])
            || $excerpt['text'][1] !== '~'
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
            || $this->urlsLinked !== true || !isset($excerpt['text'][2]) || $excerpt['text'][2] !== '/'
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
        if (\preg_match('/^(==)([^=]*?)(==)/', $excerpt['text'], $matches) !== 1) {
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
        if (\preg_match('/^(?<!\[)(?:\[\[([^\[\]]*|[\[\]])\]\])(?!\])/s', $excerpt['text'], $matches) !== 1) {
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
     * Handle smartypants
     *
     * @param array{text:string, context:string, before:string} $excerpt Inline data
     *
     * @return null|array{extent:int, element:array}
     *
     * @since 1.0.0
     */
    protected function inlineSmartypants(array $excerpt) : ?array
    {
        if (\preg_match('/(``)(?!\s)([^"\'`]{1,})(\'\')|(\")(?!\s)([^\"]{1,})(\")|(\')(?!\s)([^\']{1,})(\')|(<{2})(?!\s)([^<>]{1,})(>{2})|(\.{3})|(-{3})|(-{2})/i', $excerpt['text'], $matches) !== 1) {
            return null;
        }

        // Substitutions
        $backtickDoublequoteOpen  = $this->options['smarty']['substitutions']['left-double-quote'] ?? '&ldquo;';
        $backtickDoublequoteClose = $this->options['smarty']['substitutions']['right-double-quote'] ?? '&rdquo;';

        $smartDoublequoteOpen  = $this->options['smarty']['substitutions']['left-double-quote'] ?? '&ldquo;';
        $smartDoublequoteClose = $this->options['smarty']['substitutions']['right-double-quote'] ?? '&rdquo;';
        $smartSinglequoteOpen  = $this->options['smarty']['substitutions']['left-single-quote'] ?? '&lsquo;';
        $smartSinglequoteClose = $this->options['smarty']['substitutions']['right-single-quote'] ?? '&rsquo;';

        $leftAngleQuote  = $this->options['smarty']['substitutions']['left-angle-quote'] ?? '&laquo;';
        $rightAngleQuote = $this->options['smarty']['substitutions']['right-angle-quote'] ?? '&raquo;';

        $matches = \array_values(\array_filter($matches));

        // Smart backticks
        $smartBackticks = $this->options['smarty']['smart_backticks'] ?? false;

        if ($smartBackticks && $matches[1] === '``') {
            $length = \strlen(\trim($excerpt['before']));
            if ($length > 0) {
                return null;
            }

            return [
                'extent'  => \strlen($matches[0]),
                'element' => [
                    'text' => \html_entity_decode($backtickDoublequoteOpen).$matches[2].\html_entity_decode($backtickDoublequoteClose),
                ],
            ];
        }

        // Smart quotes
        $smartQuotes = $this->options['smarty']['smart_quotes'] ?? true;

        if ($smartQuotes) {
            if ($matches[1] === "'") {
                $length = \strlen(\trim($excerpt['before']));
                if ($length > 0) {
                    return null;
                }

                return [
                    'extent'  => \strlen($matches[0]),
                    'element' => [
                        'text' => \html_entity_decode($smartSinglequoteOpen).$matches[2].\html_entity_decode($smartSinglequoteClose),
                    ],
                ];
            }

            if ($matches[1] === '"') {
                $length = \strlen(\trim($excerpt['before']));
                if ($length > 0) {
                    return null;
                }

                return [
                    'extent'  => \strlen($matches[0]),
                    'element' => [
                        'text' => \html_entity_decode($smartDoublequoteOpen).$matches[2].\html_entity_decode($smartDoublequoteClose),
                    ],
                ];
            }
        }

        // Smart angled quotes
        $smartAngledQuotes = $this->options['smarty']['smart_angled_quotes'] ?? true;

        if ($smartAngledQuotes && $matches[1] === '<<') {
            $length = \strlen(\trim($excerpt['before']));
            if ($length > 0) {
                return null;
            }

            return [
                'extent'  => \strlen($matches[0]),
                'element' => [
                    'text' => \html_entity_decode($leftAngleQuote).$matches[2].\html_entity_decode($rightAngleQuote),
                ],
            ];
        }

        // Smart dashes
        $smartDashes = $this->options['smarty']['smart_dashes'] ?? true;

        if ($smartDashes) {
            if ($matches[1] === '---') {
                return [
                    'extent'  => \strlen($matches[0]),
                    'element' => [
                        'rawHtml' => $this->options['smarty']['substitutions']['mdash'] ?? '&mdash;',
                    ],
                ];
            }

            if ($matches[1] === '--') {
                return [
                    'extent'  => \strlen($matches[0]),
                    'element' => [
                        'rawHtml' => $this->options['smarty']['substitutions']['ndash'] ?? '&ndash;',
                    ],
                ];
            }
        }

        // Smart ellipses
        $smartEllipses = $this->options['smarty']['smart_ellipses'] ?? true;

        if ($smartEllipses && $matches[1] === '...') {
            return [
                'extent'  => \strlen($matches[0]),
                'element' => [
                    'rawHtml' => $this->options['smarty']['substitutions']['ellipses'] ?? '&hellip;',
                ],
            ];
        }

        return null;
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
            || \in_array($excerpt['text'][1], $this->specialCharacters)
        ) {
            return null;
        }

        $state = $this->options['math'] ?? false;
        if (!$state
            || ($state && !\preg_match('/^(?<!\\\\)(?<!\\\\\()\\\\\((.{2,}?)(?<!\\\\\()\\\\\)(?!\\\\\))/s', $excerpt['text']))
        ) {
            return [
                'extent' => 2,
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
    protected function blockFootnote(array $line, array $_ = null) : ?array
    {
        return ($this->options['footnotes'] ?? true)
            ? $this->blockFootnoteBase($line)
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
    protected function blockDefinitionList(array $line, array $block = null) : ?array
    {
        return ($this->options['definition_lists'] ?? true)
            ? $this->blockDefinitionListBase($line, $block)
            : null;
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
    protected function blockCode(array $line, array $block = null) : ?array
    {
        if (!($this->options['code']['blocks'] ?? true)
            || !($this->options['code'] ?? true)
        ) {
            return null;
        }

        return $this->blockCodeBase($line, $block);
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
    protected function blockComment(array $line, array $_ = null) : ?array
    {
        return ($this->options['comments'] ?? true)
            ? $this->blockCommentBase($line)
            : null;
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
    protected function blockHeader(array $line, array $_ = null) : ?array
    {
        if (!($this->options['headings'] ?? true)) {
            return null;
        }

        $block = $this->blockHeaderBase($line);
        if (empty($block)) {
            return null;
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
     * @param array{body:string, indent:int, text:string} $line  Line data
     * @param null|array                                  $block Current block
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockList(array $line, array $block = null) : ?array
    {
        return ($this->options['lists'] ?? true)
            ? $this->blockListBase($line, $block)
            : null;
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
    protected function blockQuote(array $line, array $_ = null) : ?array
    {
        return ($this->options['qoutes'] ?? true)
            ? $this->blockQuoteBase($line)
            : null;
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
    protected function blockRule(array $line, array $_ = null) : ?array
    {
        return ($this->options['thematic_breaks'] ?? true)
            ? $this->blockRuleBase($line)
            : null;
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
    protected function blockSetextHeader(array $line, array $block = null) : ?array
    {
        if (!($this->options['headings'] ?? true)) {
            return null;
        }

        $block = $this->blockSetextHeaderBase($line, $block);
        if (empty($block)) {
            return null;
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
    protected function blockMarkup(array $line, array $_ = null) : ?array
    {
        return ($this->options['markup'] ?? true)
            ? $this->blockMarkupBase($line)
            : null;
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
    protected function blockReference(array $line, array $_ = null) : ?array
    {
        return ($this->options['references'] ?? true)
            ? $this->blockReferenceBase($line)
            : null;
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
    protected function blockTable(array $line, array $block = null) : ?array
    {
        return ($this->options['tables'] ?? true)
            ? $this->blockTableBase($line, $block)
            : null;
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
    protected function blockAbbreviation(array $line, array $_ = null) : ?array
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

        return $allowCustomAbbr
            ? $this->blockAbbreviationBase($line)
            : null;
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
    protected function blockMath(array $line, array $_ = null) : ?array
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
     * @return null|array
     *
     * @since 1.0.0
     */
    protected function blockMathComplete(array $block) : ?array
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
    protected function blockFencedCode(array $line, array $_ = null) : ?array
    {
        if (!($this->options['code']['blocks'] ?? true)
            || !($this->options['code'] ?? true)
        ) {
            return null;
        }

        $block = $this->blockFencedCodeBase($line);
        if (!($this->options['diagrams'] ?? true)) {
            return $block;
        }

        $marker       = $line['text'][0];
        $openerLength = \strspn($line['text'], $marker);
        $language     = \trim(\preg_replace('/^`{3}([^\s]+)(.+)?/s', '$1', $line['text']));

        if (\strtolower($language) === 'mermaid') {
            // Mermaid.js https://mermaidjs.github.io
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
                        'class' => 'mermaid',
                    ],
                ],
            ];
        } elseif (\strtolower($language) === 'chart') {
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
        }

        return $block;
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

        if (!isset($block)) {
            return null;
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
                    && ($element['attributes']['colspan'] ?: null) === ($rows[$rowNo + $rowspan]['elements'][$index]['attributes']['colspan'] ?: null)
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
    protected function blockCheckbox(array $line, array $_ = null) : ?array
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
        $html = $block['handler'] === 'unchecked'
            ? $this->checkboxUnchecked($block['text'])
            : $this->checkboxChecked($block['text']);

        $block['element'] = [
            'rawHtml'                => $html,
            'allowRawHtmlInSafeMode' => true,
        ];

        return $block;
    }

    /**
     * Generate unchecked checkbox html
     *
     * @param string Checkbox text
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function checkboxUnchecked(string $text) : string
    {
        if ($this->markupEscaped || $this->safeMode) {
            $text = self::escape($text);
        }

        return '<input type="checkbox" disabled /> ' . $this->formatOnce($text);
    }

    /**
     * Generate checked checkbox html
     *
     * @param string Checkbox text
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function checkboxChecked(string $text) : string
    {
        if ($this->markupEscaped || $this->safeMode) {
            $text = self::escape($text);
        }

        return '<input type="checkbox" checked disabled /> ' . $this->formatOnce($text);
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
        $this->setMarkupEscaped(false);
        $this->setSafeMode(false);

        // format line
        $text = $this->line($text);

        // reset old values
        $this->setMarkupEscaped($markupEscaped);
        $this->setSafeMode($safeMode);

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
        return ($this->options['special_attributes'] ?? true)
            ? $this->parseAttributeDataBase($attribute)
            : [];
    }

    /**
     * Encodes the ToC tag to a hashed tag and replace.
     *
     * This is used to avoid parsing user defined ToC tag which includes "_" in
     * their tag such as "[[_toc_]]". Unless it will be parsed as:
     *   "<p>[[<em>TOC</em>]]</p>"
     */
    protected function encodeTagToHash($text)
    {
        $salt      = $this->getSalt();
        $tagOrigin = $this->getTagToC();

        if (\strpos($text, $tagOrigin) === false) {
            return $text;
        }

        $tagHashed = \hash('sha256', $salt.$tagOrigin);

        return \str_replace($tagOrigin, $tagHashed, $text);
    }

    /**
     * Decodes the hashed ToC tag to an original tag and replaces.
     *
     * This is used to avoid parsing user defined ToC tag which includes "_" in
     * their tag such as "[[_toc_]]". Unless it will be parsed as:
     *   "<p>[[<em>TOC</em>]]</p>"
     */
    protected function decodeTagFromHash($text)
    {
        $salt      = $this->getSalt();
        $tagOrigin = $this->getTagToC();
        $tagHashed = \hash('sha256', $salt.$tagOrigin);

        if (\strpos($text, $tagHashed) === false) {
            return $text;
        }

        return \str_replace($tagHashed, $tagOrigin, $text);
    }

    /**
     * Unique string to use as a salt value.
     */
    protected function getSalt()
    {
        static $salt;
        if (isset($salt)) {
            return $salt;
        }

        $salt = \hash('md5', (string) \time());

        return $salt;
    }

    /**
     * Gets the markdown tag for ToC.
     */
    protected function getTagToC()
    {
        return $this->options['toc']['set_toc_tag'] ?? '[toc]';
    }

    /**
     * Gets the ID attribute of the ToC for HTML tags.
     */
    protected function getIdAttributeToC()
    {
        if (!empty($this->idToc)) {
            return $this->idToc;
        }

        return 'toc';
    }

    /**
     * Generates an anchor text that are link-able even if the heading is not in
     * ASCII.
     */
    protected function createAnchorID($str) : string
    {
        $optionUrlEncode = $this->options['toc']['urlencode'] ?? false;
        if ($optionUrlEncode) {
            // Check AnchorID is unique
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
        $optionTransliterate = $this->options['toc']['transliterate'] ?? false;
        if ($optionTransliterate) {
            $str = \str_replace(\array_keys($charMap), $charMap, $str);
        }

        // Replace non-alphanumeric characters with our delimiter
        $optionDelimiter = $this->options['toc']['delimiter'] ?? '-';
        $str             = \preg_replace('/[^\p{L}\p{Nd}]+/u', $optionDelimiter, $str);

        // Remove duplicate delimiters
        $str = \preg_replace('/('.\preg_quote($optionDelimiter, '/').'){2,}/', '$1', $str);

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
     * Get only the text from a markdown string.
     * It parses to HTML once then trims the tags to get the text.
     */
    protected function fetchText($text) : string
    {
        return \trim(\strip_tags($this->line($text)));
    }

    /**
     * Set/stores the heading block to ToC list in a string and array format.
     */
    protected function setContentsList(array $Content) : void
    {
        // Stores as an array
        $this->setContentsListAsArray($Content);
        // Stores as string in markdown list format.
        $this->setContentsListAsString($Content);
    }

    /**
     * Sets/stores the heading block info as an array.
     */
    protected function setContentsListAsArray(array $Content) : void
    {
        $this->contentsListArray[] = $Content;
    }

    /**
     * Sets/stores the heading block info as a list in markdown format.
     */
    protected function setContentsListAsString(array $Content) : void
    {
        $text  = $this->fetchText($Content['text']);
        $id    = $Content['id'];
        $level = (int) \trim($Content['level'], 'h');
        $link  = "[{$text}](#{$id})";

        if ($this->firstHeadLevel === 0) {
            $this->firstHeadLevel = $level;
        }
        $cutIndent = $this->firstHeadLevel - 1;
        $level     = $cutIndent > $level ? 1 : $level - $cutIndent;

        $indent = \str_repeat('  ', $level);

        // Stores in markdown list format as below:
        // - [Header1](#Header1)
        //   - [Header2-1](#Header2-1)
        //     - [Header3](#Header3)
        //   - [Header2-2](#Header2-2)
        // ...
        $this->contentsListString .= "{$indent}- {$link}".\PHP_EOL;
    }

    /**
     * Collect and count anchors in use to prevent duplicated ids. Return string
     * with incremental, numeric suffix. Also init optional blacklist of ids.
     */
    protected function incrementAnchorId($str)
    {
        // add blacklist to list of used anchors
        if (!$this->isBlacklistInitialized) {
            $this->initBlacklist();
        }

        $this->anchorDuplicates[$str] = isset($this->anchorDuplicates[$str]) ? ++$this->anchorDuplicates[$str] : 0;

        $newStr = $str;

        if ($count = $this->anchorDuplicates[$str]) {
            $newStr .= "-{$count}";

            // increment until conversion doesn't produce new duplicates anymore
            if (isset($this->anchorDuplicates[$newStr])) {
                $newStr = $this->incrementAnchorId($str);
            } else {
                $this->anchorDuplicates[$newStr] = 0;
            }
        }

        return $newStr;
    }

    /**
     * Add blacklisted ids to anchor list.
     */
    protected function initBlacklist() : void
    {
        if ($this->isBlacklistInitialized) {
            return;
        }

        if (!empty($this->options['headings']['blacklist']) && \is_array($this->options['headings']['blacklist'])) {
            foreach ($this->options['headings']['blacklist'] as $v) {
                if (\is_string($v)) {
                    $this->anchorDuplicates[$v] = 0;
                }
            }
        }

        $this->isBlacklistInitialized = true;
    }

    protected function lineElements($text, $nonNestables = [])
    {
        $Elements = [];

        $nonNestables = empty($nonNestables)
            ? []
            : \array_combine($nonNestables, $nonNestables);

        // $excerpt is based on the first occurrence of a marker

        while ($exc = \strpbrk($text, $this->inlineMarkerList)) {
            $marker = $exc[0];

            $markerPosition = \strlen($text) - \strlen($exc);

            // Get the first char before the marker
            $beforeMarkerPosition = $markerPosition - 1;
            $charBeforeMarker     = $beforeMarkerPosition >= 0 ? $text[$markerPosition - 1] : '';

            $excerpt = ['text' => $exc, 'context' => $text, 'before' => $charBeforeMarker];

            foreach ($this->InlineTypes[$marker] as $inlineType) {
                // check to see if the current inline type is nestable in the current context

                if (isset($nonNestables[$inlineType])) {
                    continue;
                }

                $Inline = $this->{"inline{$inlineType}"}($excerpt);

                if (!isset($Inline)) {
                    continue;
                }

                // makes sure that the inline belongs to "our" marker

                if (isset($Inline['position']) && $Inline['position'] > $markerPosition) {
                    continue;
                }

                // sets a default inline position

                if (!isset($Inline['position'])) {
                    $Inline['position'] = $markerPosition;
                }

                // cause the new element to 'inherit' our non nestables

                $Inline['element']['nonNestables'] = isset($Inline['element']['nonNestables'])
                    ? \array_merge($Inline['element']['nonNestables'], $nonNestables)
                    : $nonNestables;

                // the text that comes before the inline
                $unmarkedText = \substr($text, 0, $Inline['position']);

                // compile the unmarked text
                $InlineText = $this->inlineText($unmarkedText);
                $Elements[] = $InlineText['element'];

                // compile the inline
                $Elements[] = $this->extractElement($Inline);

                // remove the examined text
                $text = \substr($text, $Inline['position'] + $Inline['extent']);

                continue 2;
            }

            // the marker does not belong to an inline

            $unmarkedText = \substr($text, 0, $markerPosition + 1);

            $InlineText = $this->inlineText($unmarkedText);
            $Elements[] = $InlineText['element'];

            $text = \substr($text, $markerPosition + 1);
        }

        $InlineText = $this->inlineText($text);
        $Elements[] = $InlineText['element'];

        foreach ($Elements as &$Element) {
            if (!isset($Element['autobreak'])) {
                $Element['autobreak'] = false;
            }
        }

        return $Elements;
    }

    private function pregReplaceAssoc(array $replace, $subject)
    {
        return \preg_replace(\array_keys($replace), \array_values($replace), $subject);
    }

    //
    // Blocks
    //

    //
    // Abbreviation

    protected function blockAbbreviationBase($Line)
    {
        if (\preg_match('/^\*\[(.+?)\]:[ ]*(.+?)[ ]*$/', $Line['text'], $matches))
        {
            $this->definitionData['Abbreviation'][$matches[1]] = $matches[2];

            return [
                'hidden' => true,
            ];
        }
    }

    //
    // Footnote

    protected function blockFootnoteBase($Line)
    {
        if (\preg_match('/^\[\^(.+?)\]:[ ]?(.*)$/', $Line['text'], $matches))
        {
            return [
                'label'  => $matches[1],
                'text'   => $matches[2],
                'hidden' => true,
            ];
        }
    }

    protected function blockFootnoteContinue($Line, $Block)
    {
        if ($Line['text'][0] === '[' && \preg_match('/^\[\^(.+?)\]:/', $Line['text']))
        {
            return;
        }

        if (isset($Block['interrupted']))
        {
            if ($Line['indent'] >= 4)
            {
                $Block['text'] .= "\n\n" . $Line['text'];

                return $Block;
            }
        } else {
            $Block['text'] .= "\n" . $Line['text'];

            return $Block;
        }
    }

    protected function blockFootnoteComplete($Block)
    {
        $this->definitionData['Footnote'][$Block['label']] = [
            'text'   => $Block['text'],
            'count'  => null,
            'number' => null,
        ];

        return $Block;
    }

    //
    // Definition List

    protected function blockDefinitionListBase($Line, $Block)
    {
        if (!isset($Block) || $Block['type'] !== 'Paragraph')
        {
            return;
        }

        $Element = [
            'name'     => 'dl',
            'elements' => [],
        ];

        $terms = \explode("\n", $Block['element']['handler']['argument']);

        foreach ($terms as $term)
        {
            $Element['elements'][] = [
                'name'    => 'dt',
                'handler' => [
                    'function'    => 'lineElements',
                    'argument'    => $term,
                    'destination' => 'elements',
                ],
            ];
        }

        $Block['element'] = $Element;

        return $this->addDdElement($Line, $Block);
    }

    protected function blockDefinitionListContinue($Line, array $Block)
    {
        if ($Line['text'][0] === ':')
        {
            return $this->addDdElement($Line, $Block);
        } else {
            if (isset($Block['interrupted']) && $Line['indent'] === 0)
            {
                return;
            }

            if (isset($Block['interrupted']))
            {
                $Block['dd']['handler']['function']  = 'textElements';
                $Block['dd']['handler']['argument'] .= "\n\n";

                $Block['dd']['handler']['destination'] = 'elements';

                unset($Block['interrupted']);
            }

            $text = \substr($Line['body'], \min($Line['indent'], 4));

            $Block['dd']['handler']['argument'] .= "\n" . $text;

            return $Block;
        }
    }

    //
    // Header

    protected function blockHeaderBase($Line)
    {
        $Block = $this->blockHeaderParent($Line);

        if ($Block !== null && \preg_match('/[ #]*{('.$this->regexAttribute.'+)}[ ]*$/', $Block['element']['handler']['argument'], $matches, \PREG_OFFSET_CAPTURE))
        {
            $attributeString = $matches[1][0];

            $Block['element']['attributes'] = $this->parseAttributeData($attributeString);

            $Block['element']['handler']['argument'] = \substr($Block['element']['handler']['argument'], 0, (int) $matches[0][1]);
        }

        return $Block;
    }

    //
    // Markup

    protected function blockMarkupBase($Line)
    {
        if ($this->markupEscaped || $this->safeMode)
        {
            return;
        }

        if (\preg_match('/^<(\w[\w-]*)(?:[ ]*'.$this->regexHtmlAttribute.')*[ ]*(\/)?>/', $Line['text'], $matches))
        {
            $element = \strtolower($matches[1]);

            if (\in_array($element, $this->textLevelElements))
            {
                return;
            }

            $Block = [
                'name'    => $matches[1],
                'depth'   => 0,
                'element' => [
                    'rawHtml'   => $Line['text'],
                    'autobreak' => true,
                ],
            ];

            $length    = \strlen($matches[0]);
            $remainder = \substr($Line['text'], $length);

            if (\trim($remainder) === '')
            {
                if (isset($matches[2]) || \in_array($matches[1], $this->voidElements))
                {
                    $Block['closed'] = true;
                    $Block['void']   = true;
                }
            }
            else
            {
                if (isset($matches[2]) || \in_array($matches[1], $this->voidElements))
                {
                    return;
                }
                if (\preg_match('/<\/'.$matches[1].'>[ ]*$/i', $remainder))
                {
                    $Block['closed'] = true;
                }
            }

            return $Block;
        }
    }

    protected function blockMarkupContinue($Line, array $Block)
    {
        if (isset($Block['closed']))
        {
            return;
        }

        if (\preg_match('/^<'.$Block['name'].'(?:[ ]*'.$this->regexHtmlAttribute.')*[ ]*>/i', $Line['text'])) // open
        {
            ++$Block['depth'];
        }

        if (\preg_match('/(.*?)<\/'.$Block['name'].'>[ ]*$/i', $Line['text'], $matches)) // close
        {
            if ($Block['depth'] > 0)
            {
                --$Block['depth'];
            }
            else
            {
                $Block['closed'] = true;
            }
        }

        if (isset($Block['interrupted']))
        {
            $Block['element']['rawHtml'] .= "\n";
            unset($Block['interrupted']);
        }

        $Block['element']['rawHtml'] .= "\n".$Line['body'];

        return $Block;
    }

    protected function blockMarkupComplete($Block)
    {
        if (!isset($Block['void']))
        {
            $Block['element']['rawHtml'] = $this->processTag($Block['element']['rawHtml']);
        }

        return $Block;
    }

    //
    // Setext

    protected function blockSetextHeaderBase($Line, array $Block = null)
    {
        $Block = $this->blockSetextHeaderParent($Line, $Block);

        if ($Block !== null && \preg_match('/[ ]*{('.$this->regexAttribute.'+)}[ ]*$/', $Block['element']['handler']['argument'], $matches, \PREG_OFFSET_CAPTURE))
        {
            $attributeString = $matches[1][0];

            $Block['element']['attributes'] = $this->parseAttributeData($attributeString);

            $Block['element']['handler']['argument'] = \substr($Block['element']['handler']['argument'], 0, (int) $matches[0][1]);
        }

        return $Block;
    }

    //
    // Inline Elements
    //

    //
    // Footnote Marker

    protected function inlineFootnoteMarker($Excerpt)
    {
        if (\preg_match('/^\[\^(.+?)\]/', $Excerpt['text'], $matches))
        {
            $name = $matches[1];

            if (!isset($this->definitionData['Footnote'][$name]))
            {
                return;
            }

            ++$this->definitionData['Footnote'][$name]['count'];

            if (!isset($this->definitionData['Footnote'][$name]['number']))
            {
                $this->definitionData['Footnote'][$name]['number'] = ++ $this->footnoteCount; // » &
            }

            $Element = [
                'name'       => 'sup',
                'attributes' => ['id' => 'fnref'.$this->definitionData['Footnote'][$name]['count'].':'.$name],
                'element'    => [
                    'name'       => 'a',
                    'attributes' => ['href' => '#fn:'.$name, 'class' => 'footnote-ref'],
                    'text'       => $this->definitionData['Footnote'][$name]['number'],
                ],
            ];

            return [
                'extent'  => \strlen($matches[0]),
                'element' => $Element,
            ];
        }
    }

    private $footnoteCount = 0;

    //
    // ~
    //

    private $currentAbreviation;

    private $currentMeaning;

    protected function insertAbreviation(array $Element)
    {
        if (isset($Element['text']))
        {
            $Element['elements'] = self::pregReplaceElements(
                '/\b'.\preg_quote($this->currentAbreviation, '/').'\b/',
                [
                    [
                        'name'       => 'abbr',
                        'attributes' => [
                            'title' => $this->currentMeaning,
                        ],
                        'text' => $this->currentAbreviation,
                    ],
                ],
                $Element['text']
            );

            unset($Element['text']);
        }

        return $Element;
    }

    protected function inlineText($text)
    {
        $Inline = $this->inlineTextParent($text);

        if (isset($this->definitionData['Abbreviation']))
        {
            foreach ($this->definitionData['Abbreviation'] as $abbreviation => $meaning)
            {
                $this->currentAbreviation = $abbreviation;
                $this->currentMeaning     = $meaning;

                $Inline['element'] = $this->elementApplyRecursiveDepthFirst(
                    [$this, 'insertAbreviation'],
                    $Inline['element']
                );
            }
        }

        return $Inline;
    }

    //
    // Util Methods
    //

    protected function addDdElement(array $Line, array $Block)
    {
        $text = \substr($Line['text'], 1);
        $text = \trim($text);

        unset($Block['dd']);

        $Block['dd'] = [
            'name'    => 'dd',
            'handler' => [
                'function'    => 'lineElements',
                'argument'    => $text,
                'destination' => 'elements',
            ],
        ];

        if (isset($Block['interrupted']))
        {
            $Block['dd']['handler']['function'] = 'textElements';

            unset($Block['interrupted']);
        }

        $Block['element']['elements'][] = & $Block['dd'];

        return $Block;
    }

    protected function buildFootnoteElement()
    {
        $Element = [
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

        \uasort($this->definitionData['Footnote'], 'self::sortFootnotes');

        foreach ($this->definitionData['Footnote'] as $definitionId => $definitionData)
        {
            if (!isset($definitionData['number']))
            {
                continue;
            }

            $text = $definitionData['text'];

            $textElements = $this->textElements($text);

            $numbers = \range(1, $definitionData['count']);

            $backLinkElements = [];

            foreach ($numbers as $number)
            {
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

            if ($textElements[$n]['name'] === 'p')
            {
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
            }
            else
            {
                $textElements[] = [
                    'name'     => 'p',
                    'elements' => $backLinkElements,
                ];
            }

            $Element['elements'][1]['elements'][] = [
                'name'       => 'li',
                'attributes' => ['id' => 'fn:'.$definitionId],
                'elements'   => \array_merge(
                    $textElements
                ),
            ];
        }

        return $Element;
    }

    // ~

    protected function parseAttributeDataBase($attributeString)
    {
        $Data = [];

        $attributes = \preg_split('/[ ]+/', $attributeString, - 1, \PREG_SPLIT_NO_EMPTY);

        foreach ($attributes as $attribute)
        {
            if ($attribute[0] === '#')
            {
                $Data['id'] = \substr($attribute, 1);
            }
            else // "."
            {
                $classes [] = \substr($attribute, 1);
            }
        }

        if (isset($classes))
        {
            $Data['class'] = \implode(' ', $classes);
        }

        return $Data;
    }

    // ~

    protected function processTag($elementMarkup) // recursive
    {
        // http://stackoverflow.com/q/1148928/200145
        \libxml_use_internal_errors(true);

        $DOMDocument = new \DOMDocument();

        // http://stackoverflow.com/q/11309194/200145
        $elementMarkup = \mb_convert_encoding($elementMarkup, 'HTML-ENTITIES', 'UTF-8');

        // http://stackoverflow.com/q/4879946/200145
        $DOMDocument->loadHTML($elementMarkup);
        $DOMDocument->removeChild($DOMDocument->doctype);
        $DOMDocument->replaceChild($DOMDocument->firstChild->firstChild->firstChild, $DOMDocument->firstChild);

        $elementText = '';

        if ($DOMDocument->documentElement->getAttribute('markdown') === '1')
        {
            foreach ($DOMDocument->documentElement->childNodes as $Node)
            {
                $elementText .= $DOMDocument->saveHTML($Node);
            }

            $DOMDocument->documentElement->removeAttribute('markdown');

            $elementText = "\n".$this->text($elementText)."\n";
        } else {
            foreach ($DOMDocument->documentElement->childNodes as $Node)
            {
                $nodeMarkup = $DOMDocument->saveHTML($Node);

                if ($Node instanceof \DOMElement && ! \in_array($Node->nodeName, $this->textLevelElements))
                {
                    $elementText .= $this->processTag($nodeMarkup);
                }
                else
                {
                    $elementText .= $nodeMarkup;
                }
            }
        }

        // because we don't want for markup to get encoded
        $DOMDocument->documentElement->nodeValue = 'placeholder\x1A';

        $markup = $DOMDocument->saveHTML($DOMDocument->documentElement);

        return \str_replace('placeholder\x1A', $elementText, $markup);
    }

    // ~

    protected function sortFootnotes($A, $B) // callback
    {
        return $A['number'] - $B['number'];
    }

    //
    // Fields
    //

    protected $regexAttribute = '(?:[#.][-\w]+[ ]*)';

    protected function textElements($text)
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

    //
    // Setters
    //

    public function setBreaksEnabled($breaksEnabled)
    {
        $this->breaksEnabled = $breaksEnabled;

        return $this;
    }

    protected $breaksEnabled;

    public function setMarkupEscaped($markupEscaped)
    {
        $this->markupEscaped = $markupEscaped;

        return $this;
    }

    protected $markupEscaped;

    public function setUrlsLinked($urlsLinked)
    {
        $this->urlsLinked = $urlsLinked;

        return $this;
    }

    protected $urlsLinked = true;

    public function setSafeMode($safeMode)
    {
        $this->safeMode = (bool) $safeMode;

        return $this;
    }

    protected $safeMode;

    public function setStrictMode($strictMode)
    {
        $this->strictMode = (bool) $strictMode;

        return $this;
    }

    protected $strictMode;

    protected $safeLinksWhitelist = [
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

    //
    // Lines
    //

    protected $BlockTypes = [
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
        '~' => ['FencedCode'],
    ];

    // ~

    protected $unmarkedBlockTypes = [
        'Code',
    ];

    //
    // Blocks
    //

    protected function lines(array $lines)
    {
        return $this->elements($this->linesElements($lines));
    }

    protected function linesElements(array $lines)
    {
        $Elements     = [];
        $CurrentBlock = null;

        foreach ($lines as $line) {
            if (\rtrim($line) === '') {
                if (isset($CurrentBlock)) {
                    $CurrentBlock['interrupted'] = (isset($CurrentBlock['interrupted'])
                        ? $CurrentBlock['interrupted'] + 1 : 1
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

            $text = $indent > 0 ? \substr($line, $indent) : $line;

            $Line = ['body' => $line, 'indent' => $indent, 'text' => $text];

            if (isset($CurrentBlock['continuable'])) {
                $methodName = 'block' . $CurrentBlock['type'] . 'Continue';
                $Block      = $this->{$methodName}($Line, $CurrentBlock);

                if (isset($Block)) {
                    $CurrentBlock = $Block;
                    continue;
                } elseif ($this->isBlockCompletable($CurrentBlock['type'])) {
                    $methodName   = 'block' . $CurrentBlock['type'] . 'Complete';
                    $CurrentBlock = $this->{$methodName}($CurrentBlock);
                }
            }

            $marker = $text[0];

            $blockTypes = $this->unmarkedBlockTypes;

            if (isset($this->BlockTypes[$marker])) {
                foreach ($this->BlockTypes[$marker] as $blockType) {
                    $blockTypes [] = $blockType;
                }
            }

            foreach ($blockTypes as $blockType) {
                $Block = $this->{"block{$blockType}"}($Line, $CurrentBlock);

                if (isset($Block)) {
                    $Block['type'] = $blockType;

                    if (!isset($Block['identified'])) {
                        if (isset($CurrentBlock)) {
                            $Elements[] = $this->extractElement($CurrentBlock);
                        }

                        $Block['identified'] = true;
                    }

                    if ($this->isBlockContinuable($blockType)) {
                        $Block['continuable'] = true;
                    }

                    $CurrentBlock = $Block;

                    continue 2;
                }
            }

            if (isset($CurrentBlock) && $CurrentBlock['type'] === 'Paragraph') {
                $Block = $this->paragraphContinue($Line, $CurrentBlock);
            }

            if (isset($Block)) {
                $CurrentBlock = $Block;
            } else {
                if (isset($CurrentBlock)) {
                    $Elements[] = $this->extractElement($CurrentBlock);
                }

                $CurrentBlock = $this->paragraph($Line);

                $CurrentBlock['identified'] = true;
            }
        }

        if (isset($CurrentBlock['continuable']) && $this->isBlockCompletable($CurrentBlock['type'])) {
            $methodName   = 'block' . $CurrentBlock['type'] . 'Complete';
            $CurrentBlock = $this->{$methodName}($CurrentBlock);
        }

        if (isset($CurrentBlock)) {
            $Elements[] = $this->extractElement($CurrentBlock);
        }

        return $Elements;
    }

    protected function extractElement(array $Component)
    {
        if (!isset($Component['element']))
        {
            if (isset($Component['markup'])) {
                $Component['element'] = ['rawHtml' => $Component['markup']];
            } elseif (isset($Component['hidden'])) {
                $Component['element'] = [];
            }
        }

        return $Component['element'];
    }

    protected function isBlockContinuable($Type) : bool
    {
        return \method_exists($this, 'block' . $Type . 'Continue');
    }

    protected function isBlockCompletable($Type) : bool
    {
        return \method_exists($this, 'block' . $Type . 'Complete');
    }

    protected function blockCodeBase($Line, $Block = null)
    {
        if (isset($Block) && $Block['type'] === 'Paragraph' && !isset($Block['interrupted'])) {
            return;
        }

        if ($Line['indent'] >= 4)
        {
            $text = \substr($Line['body'], 4);

            return [
                'element' => [
                    'name'    => 'pre',
                    'element' => [
                        'name' => 'code',
                        'text' => $text,
                    ],
                ],
            ];
        }
    }

    protected function blockCodeContinue($Line, $Block)
    {
        if ($Line['indent'] >= 4)
        {
            if (isset($Block['interrupted']))
            {
                $Block['element']['element']['text'] .= \str_repeat("\n", $Block['interrupted']);

                unset($Block['interrupted']);
            }

            $Block['element']['element']['text'] .= "\n";

            $text = \substr($Line['body'], 4);

            $Block['element']['element']['text'] .= $text;

            return $Block;
        }
    }

    protected function blockCodeComplete($Block)
    {
        return $Block;
    }

    //
    // Comment

    protected function blockCommentBase($Line)
    {
        if ($this->markupEscaped || $this->safeMode)
        {
            return;
        }

        if (\str_starts_with($Line['text'], '<!--'))
        {
            $Block = [
                'element' => [
                    'rawHtml'   => $Line['body'],
                    'autobreak' => true,
                ],
            ];

            if (\strpos($Line['text'], '-->') !== false)
            {
                $Block['closed'] = true;
            }

            return $Block;
        }
    }

    protected function blockCommentContinue($Line, array $Block)
    {
        if (isset($Block['closed']))
        {
            return;
        }

        $Block['element']['rawHtml'] .= "\n" . $Line['body'];

        if (\strpos($Line['text'], '-->') !== false)
        {
            $Block['closed'] = true;
        }

        return $Block;
    }

    //
    // Fenced Code

    protected function blockFencedCodeBase($Line)
    {
        $marker = $Line['text'][0];

        $openerLength = \strspn($Line['text'], $marker);

        if ($openerLength < 3)
        {
            return;
        }

        $infostring = \trim(\substr($Line['text'], $openerLength), "\t ");

        if (\strpos($infostring, '`') !== false)
        {
            return;
        }

        $Element = [
            'name' => 'code',
            'text' => '',
        ];

        if ($infostring !== '')
        {
            /**
             * https://www.w3.org/TR/2011/WD-html5-20110525/elements.html#classes
             * Every HTML element may have a class attribute specified.
             * The attribute, if specified, must have a value that is a set
             * of space-separated tokens representing the various classes
             * that the element belongs to.
             * [...]
             * The space characters, for the purposes of this specification,
             * are U+0020 SPACE, U+0009 CHARACTER TABULATION (tab),
             * U+000A LINE FEED (LF), U+000C FORM FEED (FF), and
             * U+000D CARRIAGE RETURN (CR).
             */
            $language = \substr($infostring, 0, \strcspn($infostring, " \t\n\f\r"));

            $Element['attributes'] = ['class' => "language-{$language}"];
        }

        return [
            'char'         => $marker,
            'openerLength' => $openerLength,
            'element'      => [
                'name'    => 'pre',
                'element' => $Element,
            ],
        ];
    }

    protected function blockFencedCodeContinue($Line, $Block)
    {
        if (isset($Block['complete']))
        {
            return;
        }

        if (isset($Block['interrupted']))
        {
            $Block['element']['element']['text'] .= \str_repeat("\n", $Block['interrupted']);

            unset($Block['interrupted']);
        }

        if (($len = \strspn($Line['text'], $Block['char'])) >= $Block['openerLength']
            && \rtrim(\substr($Line['text'], $len), ' ') === ''
        ) {
            $Block['element']['element']['text'] = \substr($Block['element']['element']['text'], 1);

            $Block['complete'] = true;

            return $Block;
        }

        $Block['element']['element']['text'] .= "\n" . $Line['body'];

        return $Block;
    }

    protected function blockFencedCodeComplete($Block)
    {
        return $Block;
    }

    //
    // Header

    protected function blockHeaderParent($Line)
    {
        $level = \strspn($Line['text'], '#');

        if ($level > 6)
        {
            return;
        }

        $text = \trim($Line['text'], '#');

        if ($this->strictMode && isset($text[0]) && $text[0] !== ' ')
        {
            return;
        }

        $text = \trim($text, ' ');

        return [
            'element' => [
                'name'    => 'h' . $level,
                'handler' => [
                    'function'    => 'lineElements',
                    'argument'    => $text,
                    'destination' => 'elements',
                ],
            ],
        ];
    }

    //
    // List

    protected function blockListBase($Line, array $CurrentBlock = null)
    {
        list($name, $pattern) = $Line['text'][0] <= '-' ? ['ul', '[*+-]'] : ['ol', '[0-9]{1,9}+[.\)]'];

        if (\preg_match('/^('.$pattern.'([ ]++|$))(.*+)/', $Line['text'], $matches))
        {
            $contentIndent = \strlen($matches[2]);

            if ($contentIndent >= 5)
            {
                --$contentIndent;
                $matches[1] = \substr($matches[1], 0, -$contentIndent);
                $matches[3] = \str_repeat(' ', $contentIndent) . $matches[3];
            }
            elseif ($contentIndent === 0)
            {
                $matches[1] .= ' ';
            }

            $markerWithoutWhitespace = \strstr($matches[1], ' ', true);

            $Block                            = [
                'indent'  => $Line['indent'],
                'pattern' => $pattern,
                'data'    => [
                    'type'       => $name,
                    'marker'     => $matches[1],
                    'markerType' => ($name === 'ul' ? $markerWithoutWhitespace : \substr($markerWithoutWhitespace, -1)),
                ],
                'element' => [
                    'name'     => $name,
                    'elements' => [],
                ],
            ];
            $Block['data']['markerTypeRegex'] = \preg_quote($Block['data']['markerType'], '/');

            if ($name === 'ol')
            {
                $listStart = \ltrim(\strstr($matches[1], $Block['data']['markerType'], true), '0') ?: '0';

                if ($listStart !== '1')
                {
                    if (isset($CurrentBlock)
                        && $CurrentBlock['type'] === 'Paragraph'
                        && !isset($CurrentBlock['interrupted'])
                    ) {
                        return;
                    }

                    $Block['element']['attributes'] = ['start' => $listStart];
                }
            }

            $Block['li'] = [
                'name'    => 'li',
                'handler' => [
                    'function'    => 'li',
                    'argument'    => empty($matches[3]) ? [] : [$matches[3]],
                    'destination' => 'elements',
                ],
            ];

            $Block['element']['elements'][] = & $Block['li'];

            return $Block;
        }
    }

    protected function blockListContinue($Line, array $Block)
    {
        if (isset($Block['interrupted']) && empty($Block['li']['handler']['argument']))
        {
            return null;
        }

        $requiredIndent = ($Block['indent'] + \strlen($Block['data']['marker']));

        if ($Line['indent'] < $requiredIndent
            && (
                (
                    $Block['data']['type'] === 'ol'
                    && \preg_match('/^[0-9]++'.$Block['data']['markerTypeRegex'].'(?:[ ]++(.*)|$)/', $Line['text'], $matches)
                ) || (
                    $Block['data']['type'] === 'ul'
                    && \preg_match('/^'.$Block['data']['markerTypeRegex'].'(?:[ ]++(.*)|$)/', $Line['text'], $matches)
                )
            )
        ) {
            if (isset($Block['interrupted']))
            {
                $Block['li']['handler']['argument'][] = '';

                $Block['loose'] = true;

                unset($Block['interrupted']);
            }

            unset($Block['li']);

            $text = isset($matches[1]) ? $matches[1] : '';

            $Block['indent'] = $Line['indent'];

            $Block['li'] = [
                'name'    => 'li',
                'handler' => [
                    'function'    => 'li',
                    'argument'    => [$text],
                    'destination' => 'elements',
                ],
            ];

            $Block['element']['elements'][] = & $Block['li'];

            return $Block;
        }
        elseif ($Line['indent'] < $requiredIndent && $this->blockList($Line))
        {
            return null;
        }

        if ($Line['text'][0] === '[' && $this->blockReference($Line))
        {
            return $Block;
        }

        if ($Line['indent'] >= $requiredIndent)
        {
            if (isset($Block['interrupted']))
            {
                $Block['li']['handler']['argument'][] = '';

                $Block['loose'] = true;

                unset($Block['interrupted']);
            }

            $text = \substr($Line['body'], $requiredIndent);

            $Block['li']['handler']['argument'][] = $text;

            return $Block;
        }

        if (!isset($Block['interrupted']))
        {
            $text = \preg_replace('/^[ ]{0,'.$requiredIndent.'}+/', '', $Line['body']);

            $Block['li']['handler']['argument'][] = $text;

            return $Block;
        }
    }

    protected function blockListComplete(array $Block)
    {
        if (isset($Block['loose']))
        {
            foreach ($Block['element']['elements'] as &$li)
            {
                if (\end($li['handler']['argument']) !== '')
                {
                    $li['handler']['argument'][] = '';
                }
            }
        }

        return $Block;
    }

    //
    // Quote

    protected function blockQuoteBase($Line)
    {
        if (\preg_match('/^>[ ]?+(.*+)/', $Line['text'], $matches))
        {
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
    }

    protected function blockQuoteContinue($Line, array $Block)
    {
        if (isset($Block['interrupted']))
        {
            return;
        }

        if ($Line['text'][0] === '>' && \preg_match('/^>[ ]?+(.*+)/', $Line['text'], $matches))
        {
            $Block['element']['handler']['argument'][] = $matches[1];

            return $Block;
        }

        if (!isset($Block['interrupted']))
        {
            $Block['element']['handler']['argument'][] = $Line['text'];

            return $Block;
        }
    }

    //
    // Rule

    protected function blockRuleBase($Line)
    {
        $marker = $Line['text'][0];

        if (\substr_count($Line['text'], $marker) >= 3 && \rtrim($Line['text'], " {$marker}") === '')
        {
            return [
                'element' => [
                    'name' => 'hr',
                ],
            ];
        }
    }

    //
    // Setext

    protected function blockSetextHeaderParent($Line, array $Block = null)
    {
        if (!isset($Block) || $Block['type'] !== 'Paragraph' || isset($Block['interrupted']))
        {
            return;
        }

        if ($Line['indent'] < 4 && \rtrim(\rtrim($Line['text'], ' '), $Line['text'][0]) === '')
        {
            $Block['element']['name'] = $Line['text'][0] === '=' ? 'h1' : 'h2';

            return $Block;
        }
    }

    //
    // Reference

    protected function blockReferenceBase($Line)
    {
        if (\strpos($Line['text'], ']') !== false
            && \preg_match('/^\[(.+?)\]:[ ]*+<?(\S+?)>?(?:[ ]+["\'(](.+)["\')])?[ ]*+$/', $Line['text'], $matches)
        ) {
            $id = \strtolower($matches[1]);

            $Data = [
                'url'   => UriFactory::build($matches[2]),
                'title' => isset($matches[3]) ? $matches[3] : null,
            ];

            $this->definitionData['Reference'][$id] = $Data;

            return [
                'element' => [],
            ];
        }
    }

    //
    // Table

    protected function blockTableBase($Line, array $Block = null)
    {
        if (!isset($Block) || $Block['type'] !== 'Paragraph' || isset($Block['interrupted']))
        {
            return;
        }

        if (\strpos($Block['element']['handler']['argument'], '|') === false
            && \strpos($Line['text'], '|') === false
            && \strpos($Line['text'], ':') === false
            || \strpos($Block['element']['handler']['argument'], "\n") !== false
        ) {
            return;
        }

        if (\rtrim($Line['text'], ' -:|') !== '')
        {
            return;
        }

        $alignments = [];

        $divider = $Line['text'];

        $divider = \trim($divider);
        $divider = \trim($divider, '|');

        $dividerCells = \explode('|', $divider);

        foreach ($dividerCells as $dividerCell)
        {
            $dividerCell = \trim($dividerCell);

            if ($dividerCell === '')
            {
                return;
            }

            $alignment = null;

            if ($dividerCell[0] === ':')
            {
                $alignment = 'left';
            }

            if (\substr($dividerCell, - 1) === ':')
            {
                $alignment = $alignment === 'left' ? 'center' : 'right';
            }

            $alignments [] = $alignment;
        }

        $HeaderElements = [];

        $header = $Block['element']['handler']['argument'];

        $header = \trim($header);
        $header = \trim($header, '|');

        $headerCells = \explode('|', $header);

        if (\count($headerCells) !== \count($alignments))
        {
            return;
        }

        foreach ($headerCells as $index => $headerCell)
        {
            $headerCell = \trim($headerCell);

            $HeaderElement = [
                'name'    => 'th',
                'handler' => [
                    'function'    => 'lineElements',
                    'argument'    => $headerCell,
                    'destination' => 'elements',
                ],
            ];

            if (isset($alignments[$index]))
            {
                $alignment = $alignments[$index];

                $HeaderElement['attributes'] = [
                    'style' => "text-align: {$alignment};",
                ];
            }

            $HeaderElements [] = $HeaderElement;
        }

        $Block = [
            'alignments' => $alignments,
            'identified' => true,
            'element'    => [
                'name'     => 'table',
                'elements' => [],
            ],
        ];

        $Block['element']['elements'][] = [
            'name' => 'thead',
        ];

        $Block['element']['elements'][] = [
            'name'     => 'tbody',
            'elements' => [],
        ];

        $Block['element']['elements'][0]['elements'][] = [
            'name'     => 'tr',
            'elements' => $HeaderElements,
        ];

        return $Block;
    }

    protected function blockTableContinue($Line, array $Block)
    {
        if (isset($Block['interrupted']))
        {
            return;
        }

        if (\count($Block['alignments']) === 1 || $Line['text'][0] === '|' || \strpos($Line['text'], '|'))
        {
            $Elements = [];

            $row = $Line['text'];

            $row = \trim($row);
            $row = \trim($row, '|');

            \preg_match_all('/(?:(\\\\[|])|[^|`]|`[^`]++`|`)++/', $row, $matches);

            $cells = \array_slice($matches[0], 0, \count($Block['alignments']));

            foreach ($cells as $index => $cell)
            {
                $cell = \trim($cell);

                $Element = [
                    'name'    => 'td',
                    'handler' => [
                        'function'    => 'lineElements',
                        'argument'    => $cell,
                        'destination' => 'elements',
                    ],
                ];

                if (isset($Block['alignments'][$index]))
                {
                    $Element['attributes'] = [
                        'style' => 'text-align: ' . $Block['alignments'][$index] . ';',
                    ];
                }

                $Elements [] = $Element;
            }

            $Element = [
                'name'     => 'tr',
                'elements' => $Elements,
            ];

            $Block['element']['elements'][1]['elements'][] = $Element;

            return $Block;
        }
    }

    //
    // ~
    //

    protected function paragraph($Line)
    {
        return [
            'type'    => 'Paragraph',
            'element' => [
                'name'    => 'p',
                'handler' => [
                    'function'    => 'lineElements',
                    'argument'    => $Line['text'],
                    'destination' => 'elements',
                ],
            ],
        ];
    }

    protected function paragraphContinue($Line, array $Block)
    {
        if (isset($Block['interrupted']))
        {
            return;
        }

        $Block['element']['handler']['argument'] .= "\n".$Line['text'];

        return $Block;
    }

    //
    // Inline Elements
    //

    protected $InlineTypes = [
        '!'  => ['Image'],
        '&'  => ['SpecialCharacter'],
        '*'  => ['Emphasis'],
        ':'  => ['Url'],
        '<'  => ['UrlTag', 'EmailTag', 'Markup'],
        '['  => ['FootnoteMarker', 'Link'],
        '_'  => ['Emphasis'],
        '`'  => ['Code'],
        '~'  => ['Strikethrough'],
        '\\' => ['EscapeSequence'],
        '='  => ['mark'],
    ];

    // ~

    protected $inlineMarkerList = '!*_&[:<`~\\';

    //
    // ~
    //

    public function line($text, $nonNestables = [])
    {
        return $this->elements($this->lineElements($text, $nonNestables));
    }

    /*
    protected function lineElements($text, $nonNestables = array())
    {
        # standardize line breaks
        $text = str_replace(array("\r\n", "\r"), "\n", $text);

        $Elements = array();

        $nonNestables = (empty($nonNestables)
            ? array()
            : array_combine($nonNestables, $nonNestables)
        );

        # $excerpt is based on the first occurrence of a marker

        while ($excerpt = strpbrk($text, $this->inlineMarkerList))
        {
            $marker = $excerpt[0];

            $markerPosition = strlen($text) - strlen($excerpt);

            $Excerpt = array('text' => $excerpt, 'context' => $text);

            foreach ($this->InlineTypes[$marker] as $inlineType)
            {
                # check to see if the current inline type is nestable in the current context

                if (isset($nonNestables[$inlineType]))
                {
                    continue;
                }

                $Inline = $this->{"inline$inlineType"}($Excerpt);

                if ( !isset($Inline))
                {
                    continue;
                }

                # makes sure that the inline belongs to "our" marker

                if (isset($Inline['position']) and $Inline['position'] > $markerPosition)
                {
                    continue;
                }

                # sets a default inline position

                if ( !isset($Inline['position']))
                {
                    $Inline['position'] = $markerPosition;
                }

                # cause the new element to 'inherit' our non nestables


                $Inline['element']['nonNestables'] = isset($Inline['element']['nonNestables'])
                    ? array_merge($Inline['element']['nonNestables'], $nonNestables)
                    : $nonNestables
                ;

                # the text that comes before the inline
                $unmarkedText = substr($text, 0, $Inline['position']);

                # compile the unmarked text
                $InlineText = $this->inlineText($unmarkedText);
                $Elements[] = $InlineText['element'];

                # compile the inline
                $Elements[] = $this->extractElement($Inline);

                # remove the examined text
                $text = substr($text, $Inline['position'] + $Inline['extent']);

                continue 2;
            }

            # the marker does not belong to an inline

            $unmarkedText = substr($text, 0, $markerPosition + 1);

            $InlineText = $this->inlineText($unmarkedText);
            $Elements[] = $InlineText['element'];

            $text = substr($text, $markerPosition + 1);
        }

        $InlineText = $this->inlineText($text);
        $Elements[] = $InlineText['element'];

        foreach ($Elements as &$Element)
        {
            if ( !isset($Element['autobreak']))
            {
                $Element['autobreak'] = false;
            }
        }

        return $Elements;
    }
    */

    //
    // ~
    //

    protected function inlineTextParent($text)
    {
        $Inline = [
            'extent'  => \strlen($text),
            'element' => [],
        ];

        $Inline['element']['elements'] = self::pregReplaceElements(
            $this->breaksEnabled ? '/[ ]*+\n/' : '/(?:[ ]*+\\\\|[ ]{2,}+)\n/',
            [
                ['name' => 'br'],
                ['text' => "\n"],
            ],
            $text
        );

        return $Inline;
    }

    protected function inlineLinkParent($Excerpt)
    {
        $Element = [
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

        $extent = 0;

        $remainder = $Excerpt['text'];

        if (\preg_match('/\[((?:[^][]++|(?R))*+)\]/', $remainder, $matches))
        {
            $Element['handler']['argument'] = $matches[1];

            $extent += \strlen($matches[0]);

            $remainder = \substr($remainder, $extent);
        } else {
            return;
        }

        if (\preg_match('/^[(]\s*+((?:[^ ()]++|[(][^ )]+[)])++)(?:[ ]+("[^"]*+"|\'[^\']*+\'))?\s*+[)]/', $remainder, $matches))
        {
            $Element['attributes']['href'] = UriFactory::build($matches[1]);

            if (isset($matches[2]))
            {
                $Element['attributes']['title'] = \substr($matches[2], 1, - 1);
            }

            $extent += \strlen($matches[0]);
        } else {
            if (\preg_match('/^\s*\[(.*?)\]/', $remainder, $matches))
            {
                $definition = \strlen($matches[1]) !== 0 ? $matches[1] : $Element['handler']['argument'];
                $definition = \strtolower($definition);

                $extent += \strlen($matches[0]);
            }
            else
            {
                $definition = \strtolower($Element['handler']['argument']);
            }

            if (!isset($this->definitionData['Reference'][$definition]))
            {
                return;
            }

            $Definition = $this->definitionData['Reference'][$definition];

            $Element['attributes']['href']  = $Definition['url'];
            $Element['attributes']['title'] = $Definition['title'];
        }

        return [
            'extent'  => $extent,
            'element' => $Element,
        ];
    }

    protected function inlineSpecialCharacter($Excerpt)
    {
        if (\substr($Excerpt['text'], 1, 1) !== ' ' && \strpos($Excerpt['text'], ';') !== false
            && \preg_match('/^&(#?+[0-9a-zA-Z]++);/', $Excerpt['text'], $matches)
        ) {
            return [
                'element' => ['rawHtml' => '&' . $matches[1] . ';'],
                'extent'  => \strlen($matches[0]),
            ];
        }
    }

    // ~

    protected function unmarkedText($text)
    {
        $Inline = $this->inlineText($text);
        return $this->element($Inline['element']);
    }

    //
    // Handlers
    //

    protected function handle(array $Element)
    {
        if (isset($Element['handler']))
        {
            if (!isset($Element['nonNestables']))
            {
                $Element['nonNestables'] = [];
            }

            if (\is_string($Element['handler']))
            {
                $function = $Element['handler'];
                $argument = $Element['text'];
                unset($Element['text']);
                $destination = 'rawHtml';
            }
            else
            {
                $function    = $Element['handler']['function'];
                $argument    = $Element['handler']['argument'];
                $destination = $Element['handler']['destination'];
            }

            $Element[$destination] = $this->{$function}($argument, $Element['nonNestables']);

            if ($destination === 'handler')
            {
                $Element = $this->handle($Element);
            }

            unset($Element['handler']);
        }

        return $Element;
    }

    protected function handleElementRecursive(array $Element)
    {
        return $this->elementApplyRecursive([$this, 'handle'], $Element);
    }

    protected function handleElementsRecursive(array $Elements)
    {
        return $this->elementsApplyRecursive([$this, 'handle'], $Elements);
    }

    protected function elementApplyRecursive($closure, array $Element)
    {
        $Element = $closure($Element);

        if (isset($Element['elements']))
        {
            $Element['elements'] = $this->elementsApplyRecursive($closure, $Element['elements']);
        }
        elseif (isset($Element['element']))
        {
            $Element['element'] = $this->elementApplyRecursive($closure, $Element['element']);
        }

        return $Element;
    }

    protected function elementApplyRecursiveDepthFirst($closure, array $Element)
    {
        if (isset($Element['elements']))
        {
            $Element['elements'] = $this->elementsApplyRecursiveDepthFirst($closure, $Element['elements']);
        }
        elseif (isset($Element['element']))
        {
            $Element['element'] = $this->elementsApplyRecursiveDepthFirst($closure, $Element['element']);
        }

        return $closure($Element);
    }

    protected function elementsApplyRecursive($closure, array $Elements)
    {
        foreach ($Elements as &$Element)
        {
            $Element = $this->elementApplyRecursive($closure, $Element);
        }

        return $Elements;
    }

    protected function elementsApplyRecursiveDepthFirst($closure, array $Elements)
    {
        foreach ($Elements as &$Element)
        {
            $Element = $this->elementApplyRecursiveDepthFirst($closure, $Element);
        }

        return $Elements;
    }

    protected function element(array $Element)
    {
        if ($this->safeMode)
        {
            $Element = $this->sanitiseElement($Element);
        }

        // identity map if element has no handler
        $Element = $this->handle($Element);

        $hasName = isset($Element['name']);

        $markup = '';

        if ($hasName)
        {
            $markup .= '<' . $Element['name'];

            if (isset($Element['attributes']))
            {
                foreach ($Element['attributes'] as $name => $value)
                {
                    if ($value === null)
                    {
                        continue;
                    }

                    $markup .= " {$name}=\"".self::escape((string) $value).'"';
                }
            }
        }

        $permitRawHtml = false;

        if (isset($Element['text']))
        {
            $text = $Element['text'];
        }
        // very strongly consider an alternative if you're writing an
        // extension
        elseif (isset($Element['rawHtml']))
        {
            $text = $Element['rawHtml'];

            $allowRawHtmlInSafeMode = isset($Element['allowRawHtmlInSafeMode']) && $Element['allowRawHtmlInSafeMode'];
            $permitRawHtml          = !$this->safeMode || $allowRawHtmlInSafeMode;
        }

        $hasContent = isset($text) || isset($Element['element']) || isset($Element['elements']);

        if ($hasContent)
        {
            $markup .= $hasName ? '>' : '';

            if (isset($Element['elements']))
            {
                $markup .= $this->elements($Element['elements']);
            }
            elseif (isset($Element['element']))
            {
                $markup .= $this->element($Element['element']);
            } elseif (!$permitRawHtml) {
                $markup .= self::escape((string) $text, true);
            }
            else
            {
                $markup .= $text;
            }

            $markup .= $hasName ? '</' . $Element['name'] . '>' : '';
        }
        elseif ($hasName)
        {
            $markup .= ' />';
        }

        return $markup;
    }

    protected function elements(array $Elements) : string
    {
        $markup = '';

        $autoBreak = true;

        foreach ($Elements as $Element)
        {
            if (empty($Element))
            {
                continue;
            }

            $autoBreakNext = (isset($Element['autobreak'])
                ? $Element['autobreak'] : isset($Element['name'])
            );
            // (autobreak === false) covers both sides of an element
            $autoBreak = $autoBreak ? $autoBreakNext : $autoBreak;

            $markup   .= ($autoBreak ? "\n" : '') . $this->element($Element);
            $autoBreak = $autoBreakNext;
        }

        return $markup . ($autoBreak ? "\n" : '');
    }

    // ~

    protected function li($lines)
    {
        $Elements = $this->linesElements($lines);

        if (! \in_array('', $lines)
            && isset($Elements[0], $Elements[0]['name'])
            && $Elements[0]['name'] === 'p'
        ) {
            unset($Elements[0]['name']);
        }

        return $Elements;
    }

    //
    // AST Convenience
    //

    /**
     * Replace occurrences $regexp with $Elements in $text. Return an array of
     * elements representing the replacement.
     */
    protected static function pregReplaceElements($regexp, $Elements, $text)
    {
        $newElements = [];

        while (\preg_match($regexp, $text, $matches, \PREG_OFFSET_CAPTURE))
        {
            $offset = (int) $matches[0][1];
            $before = \substr($text, 0, $offset);
            $after  = \substr($text, $offset + \strlen($matches[0][0]));

            $newElements[] = ['text' => $before];

            foreach ($Elements as $Element)
            {
                $newElements[] = $Element;
            }

            $text = $after;
        }

        $newElements[] = ['text' => $text];

        return $newElements;
    }

    //
    // Deprecated Methods
    //

    public static function parse($text)
    {
        $parsedown = new self();

        return $parsedown->text($text);
    }

    protected function sanitiseElement(array $Element)
    {
        static $goodAttribute    = '/^[a-zA-Z0-9][a-zA-Z0-9-_]*+$/';
        static $safeUrlNameToAtt = [
            'a'   => 'href',
            'img' => 'src',
        ];

        if (!isset($Element['name']))
        {
            unset($Element['attributes']);
            return $Element;
        }

        if (isset($safeUrlNameToAtt[$Element['name']]))
        {
            $Element = $this->filterUnsafeUrlInAttribute($Element, $safeUrlNameToAtt[$Element['name']]);
        }

        if (! empty($Element['attributes']))
        {
            foreach ($Element['attributes'] as $att => $val)
            {
                // filter out badly parsed attribute
                if (! \preg_match($goodAttribute, $att))
                {
                    unset($Element['attributes'][$att]);
                }
                // dump onevent attribute
                elseif (self::striAtStart($att, 'on'))
                {
                    unset($Element['attributes'][$att]);
                }
            }
        }

        return $Element;
    }

    protected function filterUnsafeUrlInAttribute(array $Element, $attribute)
    {
        foreach ($this->safeLinksWhitelist as $scheme)
        {
            if (self::striAtStart($Element['attributes'][$attribute], $scheme))
            {
                return $Element;
            }
        }

        $Element['attributes'][$attribute] = \str_replace(':', '%3A', $Element['attributes'][$attribute]);

        return $Element;
    }

    //
    // Static Methods
    //

    protected static function escape(string $text, bool $allowQuotes = false) : string
    {
        return \htmlspecialchars($text, $allowQuotes ? \ENT_NOQUOTES : \ENT_QUOTES, 'UTF-8');
    }

    protected static function striAtStart($string, $needle)
    {
        $len = \strlen($needle);

        if ($len > \strlen($string))
        {
            return false;
        } else {
            return \strtolower(\substr($string, 0, $len)) === \strtolower($needle);
        }
    }
}
