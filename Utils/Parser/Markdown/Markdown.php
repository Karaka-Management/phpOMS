<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package    phpOMS\Utils\Parser\Markdown
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @license    Original license Emanuil Rusev, erusev.com (MIT)
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils\Parser\Markdown;

use phpOMS\Uri\UriFactory;

/**
 * Markdown parser class.
 *
 * @package    phpOMS\Utils\Parser\Markdown
 * @license    OMS License 1.0
 * @license    Original license Emanuil Rusev, erusev.com (MIT)
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class Markdown
{
    /**
     * Blocktypes.
     *
     * @var string[][]
     * @since 1.0.0
     */
    protected static $blockTypes = [
        '#' => ['Header'],
        '*' => ['Rule', 'List'],
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
        ':' => ['Table'],
        '=' => ['SetextHeader'],
        '>' => ['Quote'],
        '[' => ['Reference'],
        '_' => ['Rule'],
        '`' => ['FencedCode'],
        '|' => ['Table'],
        '~' => ['FencedCode'],
    ];

    /**
     * Blocktypes.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static $unmarkedBlockTypes = [
        'Code',
    ];

    /**
     * Special reserved characters.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static $specialCharacters = [
        '\\', '`', '*', '_', '{', '}', '[', ']', '(', ')', '>', '#', '+', '-', '.', '!', '|',
    ];

    /**
     * Regex for strong.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static $strongRegex = [
        '*' => '/^[*]{2}((?:\\\\\*|[^*]|[*][^*]*[*])+?)[*]{2}(?![*])/s',
    ];

    /**
     * Regex for underline.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static $underlineRegex = [
        '_' => '/^__((?:\\\\_|[^_]|_[^_]*_)+?)__(?!_)/us',
    ];

    /**
     * Regex for em.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static $emRegex = [
        '*' => '/^[*]((?:\\\\\*|[^*]|[*][*][^*]+?[*][*])+?)[*](?![*])/s',
        '_' => '/^_((?:\\\\_|[^_]|__[^_]*__)+?)_(?!_)\b/us',
    ];

    /**
     * Regex for identifying html attributes.
     *
     * @var string
     * @since 1.0.0
     */
    protected static $regexHtmlAttribute = '[a-zA-Z_:][\w:.-]*(?:\s*=\s*(?:[^"\'=<>`\s]+|"[^"]*"|\'[^\']*\'))?';

    /**
     * Void elements.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static $voidElements = [
        'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source',
    ];

    /**
     * Text elements.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static $textLevelElements = [
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
     * Inline identifiers.
     *
     * @var string[][]
     * @since 1.0.0
     */
    protected static $inlineTypes = [
        '"'  => ['SpecialCharacter'],
        '!'  => ['Image'],
        '&'  => ['SpecialCharacter'],
        '*'  => ['Emphasis'],
        ':'  => ['Url'],
        '<'  => ['UrlTag', 'EmailTag', 'SpecialCharacter'],
        '>'  => ['SpecialCharacter'],
        '['  => ['Link'],
        '_'  => ['Emphasis'],
        '`'  => ['Code'],
        '~'  => ['Strikethrough'],
        '\\' => ['EscapeSequence'],
    ];

    /**
     * List of inline start markers.
     *
     * @var string
     * @since 1.0.0
     */
    protected static $inlineMarkerList = '!"*_&[:<>`~\\';

    /**
     * Continuable elements.
     *
     * @var string[]
     * @since 1.0.0
     */
    private static $continuable = [
        'Code', 'FencedCode', 'List', 'Quote', 'Table',
    ];

    /**
     * Completable elments.
     *
     * @var string[]
     * @since 1.0.0
     */
    private static $completable = [
        'Code', 'FencedCode',
    ];

    /**
     * Safe link types whitelist.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static $safeLinksWhitelist = [
        'http://', 'https://', 'ftp://', 'ftps://', 'mailto:',
        'data:image/png;base64,', 'data:image/gif;base64,', 'data:image/jpeg;base64,',
        'irc:', 'ircs:', 'git:', 'ssh:', 'news:', 'steam:',
    ];

    /**
     * Some definition data for elements
     *
     * @var string[]
     * @since 1.0.0
     */
    private static $definitionData = [];

    /**
     * Parse markdown
     *
     * @param string $text Markdown text
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function parse(string $text) : string
    {
        self::$definitionData = [];

        $text   = \str_replace(["\r\n", "\r"], "\n", $text);
        $text   = \trim($text, "\n");
        $lines  = \explode("\n", $text);
        $markup = self::lines($lines);

        return \trim($markup, "\n");
    }

    /**
     * Parse lines
     *
     * @param string[] $lines Markdown lines
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected static function lines(array $lines) : string
    {
        $currentBlock = null;

        foreach ($lines as $line) {
            if (\rtrim($line) === '') {
                if (isset($currentBlock)) {
                    $currentBlock['interrupted'] = true;
                }

                continue;
            }

            if (\strpos($line, "\t") !== false) {
                $parts = \explode("\t", $line);
                $line  = $parts[0];

                unset($parts[0]);

                foreach ($parts as $part) {
                    $shortage = 4 - \mb_strlen($line, 'utf-8') % 4;

                    $line .= \str_repeat(' ', $shortage);
                    $line .= $part;
                }
            }

            $indent = 0;
            while (isset($line[$indent]) && $line[$indent] === ' ') {
                ++$indent;
            }

            $text      = $indent > 0 ? \substr($line, $indent) : $line;
            $lineArray = ['body' => $line, 'indent' => $indent, 'text' => $text];

            if (isset($currentBlock['continuable'])) {
                $block = self::{'block' . $currentBlock['type'] . 'Continue'}($lineArray, $currentBlock);

                if ($block !== null) {
                    $currentBlock = $block;

                    continue;
                } elseif (\in_array($currentBlock['type'], self::$completable)) {
                    $currentBlock = self::{'block' . $currentBlock['type'] . 'Complete'}($currentBlock);
                }
            }

            $marker     = $text[0];
            $blockTypes = self::$unmarkedBlockTypes;

            if (isset(self::$blockTypes[$marker])) {
                foreach (self::$blockTypes[$marker] as $blockType) {
                    $blockTypes[] = $blockType;
                }
            }

            foreach ($blockTypes as $blockType) {
                $block = self::{'block' . $blockType}($lineArray, $currentBlock);

                if ($block !== null) {
                    $block['type'] = $blockType;

                    if (!isset($block['identified'])) {
                        $blocks[] = $currentBlock;

                        $block['identified'] = true;
                    }

                    if (\in_array($blockType, self::$continuable)) {
                        $block['continuable'] = true;
                    }

                    $currentBlock = $block;

                    continue 2;
                }
            }

            if (isset($currentBlock) && !isset($currentBlock['type']) && !isset($currentBlock['interrupted'])) {
                $currentBlock['element']['text'] .= "\n" . $text;
            } else {
                $blocks[]                   = $currentBlock;
                $currentBlock               = self::paragraph($lineArray);
                $currentBlock['identified'] = true;
            }
        }

        if (isset($currentBlock['continuable']) && \in_array($currentBlock['type'], self::$completable)) {
            $currentBlock = self::{'block' . $currentBlock['type'] . 'Complete'}($currentBlock);
        }

        $blocks[] = $currentBlock;
        unset($blocks[0]);
        $markup = '';

        foreach ($blocks as $block) {
            if (isset($block['hidden'])) {
                continue;
            }

            $markup .= "\n";
            $markup .= isset($block['markup']) ? $block['markup'] : self::element($block['element']);
        }

        $markup .= "\n";

        return $markup;
    }

    /**
     * Handle block code
     *
     * @param array $lineArray Line information
     * @param array $block     Block information
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function blockCode(array $lineArray, array $block = null) : ?array
    {
        if ($block !== null && !isset($block['type']) && !isset($block['interrupted'])) {
            return null;
        }

        if ($lineArray['indent'] < 4) {
            return null;
        }

        return [
            'element' => [
                'name'    => 'pre',
                'handler' => 'element',
                'text'    => [
                    'name' => 'code',
                    'text' => \substr($lineArray['body'], 4),
                ],
            ],
        ];
    }

    /**
     * Handle continuable block code
     *
     * @param array $lineArray Line information
     * @param array $block     Block information
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function blockCodeContinue(array $lineArray, array $block) : ?array
    {
        if ($lineArray['indent'] < 4) {
            return null;
        }

        if (isset($block['interrupted'])) {
            $block['element']['text']['text'] .= "\n";

            unset($block['interrupted']);
        }

        $block['element']['text']['text'] .= "\n";
        $block['element']['text']['text'] .= \substr($lineArray['body'], 4);

        return $block;
    }

    /**
     * Handle completed code
     *
     * @param array $block Block information
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function blockCodeComplete(?array $block) : ?array
    {
        return $block;
    }

    /**
     * Handle fenced code
     *
     * @param array $lineArray Line information
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function blockFencedCode(array $lineArray) : ?array
    {
        if (!\preg_match('/^[' . $lineArray['text'][0] . ']{3,}[ ]*([^`]+)?[ ]*$/', $lineArray['text'], $matches)) {
            return null;
        }

        $elementArray = [
            'name' => 'code',
            'text' => '',
        ];

        if (isset($matches[1])) {
            $elementArray['attributes'] = [
                'class' => 'language-' . $matches[1],
            ];
        }

        return [
            'char'    => $lineArray['text'][0],
            'element' => [
                'name'    => 'pre',
                'handler' => 'element',
                'text'    => $elementArray,
            ],
        ];
    }

    /**
     * Handle continued fenced code
     *
     * @param array $lineArray Line information
     * @param array $block     Block information
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function blockFencedCodeContinue(array $lineArray, array $block) : ?array
    {
        if (isset($block['complete'])) {
            return null;
        }

        if (isset($block['interrupted'])) {
            $block['element']['text']['text'] .= "\n";

            unset($block['interrupted']);
        }

        if (\preg_match('/^' . $block['char'] . '{3,}[ ]*$/', $lineArray['text'])) {
            $block['element']['text']['text'] = \substr($block['element']['text']['text'], 1);
            $block['complete']                = true;

            return $block;
        }

        $block['element']['text']['text'] .= "\n" . $lineArray['body'];

        return $block;
    }

    /**
     * Handle completed fenced block code
     *
     * @param array $block Block information
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function blockFencedCodeComplete(?array $block) : ?array
    {
        return $block;
    }

    /**
     * Handle header element
     *
     * @param array $lineArray Line information
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function blockHeader(array $lineArray) : ?array
    {
        if (!isset($lineArray['text'][1])) {
            return null;
        }

        $level = 1;
        while (isset($lineArray['text'][$level]) && $lineArray['text'][$level] === '#') {
            ++$level;
        }

        if ($level > 6) {
            return null;
        }

        return [
            'element' => [
                'name'    => 'h' . \min(6, $level),
                'text'    => \trim($lineArray['text'], '# '),
                'handler' => 'line',
            ],
        ];
    }

    /**
     * Handle list
     *
     * @param array $lineArray Line information
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function blockList(array $lineArray) : ?array
    {
        list($name, $pattern) = $lineArray['text'][0] <= '-' ? ['ul', '[*+-]'] : ['ol', '[0-9]+[.]'];

        if (!\preg_match('/^(' . $pattern . '[ ]+)(.*)/', $lineArray['text'], $matches)) {
            return null;
        }

        $block = [
            'indent'  => $lineArray['indent'],
            'pattern' => $pattern,
            'element' => [
                'name'    => $name,
                'handler' => 'elements',
            ],
        ];

        if ($name === 'ol') {
            $listStart = \stristr($matches[0], '.', true);

            if ($listStart !== '1') {
                $block['element']['attributes'] = ['start' => $listStart];
            }
        }

        $block['li'] = [
            'name'    => 'li',
            'handler' => 'li',
            'text'    => [
                $matches[2],
            ],
        ];

        $block['element']['text'][] = &$block['li'];

        return $block;
    }

    /**
     * Handle continue list
     *
     * @param array $lineArray Line information
     * @param array $block     Block information
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function blockListContinue(array $lineArray, array $block) : ?array
    {
        if ($block['indent'] === $lineArray['indent'] && \preg_match('/^' . $block['pattern'] . '(?:[ ]+(.*)|$)/', $lineArray['text'], $matches)) {
            if (isset($block['interrupted'])) {
                $block['li']['text'][] = '';

                unset($block['interrupted']);
            }

            unset($block['li']);

            $block['li'] = [
                'name'    => 'li',
                'handler' => 'li',
                'text'    => [
                    isset($matches[1]) ? $matches[1] : '',
                ],
            ];

            $block['element']['text'][] = &$block['li'];

            return $block;
        }

        if ($lineArray['text'][0] === '[' && self::blockReference($lineArray)) {
            return $block;
        }

        if (!isset($block['interrupted'])) {
            $block['li']['text'][] = \preg_replace('/^[ ]{0,4}/', '', $lineArray['body']);

            return $block;
        }

        if ($lineArray['indent'] > 0) {
            $block['li']['text'][] = '';
            $block['li']['text'][] = \preg_replace('/^[ ]{0,4}/', '', $lineArray['body']);

            unset($block['interrupted']);

            return $block;
        }

        return null;
    }

    /**
     * Handle block quote
     *
     * @param array $lineArray Line information
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function blockQuote(array $lineArray) : ?array
    {
        if (!\preg_match('/^>[ ]?(.*)/', $lineArray['text'], $matches)) {
            return null;
        }

        return [
            'element' => [
                'name'    => 'blockquote',
                'handler' => 'lines',
                'text'    => (array) $matches[1],
            ],
        ];
    }

    /**
     * Handle continue quote
     *
     * @param array $lineArray Line information
     * @param array $block     Block information
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function blockQuoteContinue(array $lineArray, array $block) : ?array
    {
        if ($lineArray['text'][0] === '>' && \preg_match('/^>[ ]?(.*)/', $lineArray['text'], $matches)) {
            if (isset($block['interrupted'])) {
                $block['element']['text'][] = '';

                unset($block['interrupted']);
            }

            $block['element']['text'][] = $matches[1];

            return $block;
        }

        if (!isset($block['interrupted'])) {
            $block['element']['text'][] = $lineArray['text'];

            return $block;
        }

        return null;
    }

    /**
     * Handle HR element
     *
     * @param array $lineArray Line information
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function blockRule(array $lineArray) : ?array
    {
        if (!\preg_match('/^([' . $lineArray['text'][0] . '])([ ]*\1){2,}[ ]*$/', $lineArray['text'])) {
            return null;
        }

        return [
            'element' => [
                'name' => 'hr',
            ],
        ];
    }

    /**
     * Handle header for '=' indicator
     *
     * @param array $lineArray Line information
     * @param array $block     Block information
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function blockSetextHeader(array $lineArray, array $block = null) : ?array
    {
        if (!isset($block) || isset($block['type']) || isset($block['interrupted'])) {
            return null;
        }

        if (\rtrim($lineArray['text'], $lineArray['text'][0]) !== '') {
            return null;
        }

        $block['element']['name'] = $lineArray['text'][0] === '=' ? 'h1' : 'h2';

        return $block;
    }

    /**
     * Handle content reference
     *
     * @param array $lineArray Line information
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function blockReference(array $lineArray) : ?array
    {
        if (!\preg_match('/^\[(.+?)\]:[ ]*<?(\S+?)>?(?:[ ]+["\'(](.+)["\')])?[ ]*$/', $lineArray['text'], $matches)) {
            return null;
        }

        $data = [
            'url'   => UriFactory::build($matches[2]),
            'title' => $matches[3] ?? null,
        ];

        self::$definitionData['Reference'][\strtolower($matches[1])] = $data;

        return ['hidden' => true];
    }

    /**
     * Handle table
     *
     * @param array $lineArray Line information
     * @param array $block     Block information
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function blockTable($lineArray, array $block = null) : ?array
    {
        if (!isset($block) || isset($block['type']) || isset($block['interrupted'])) {
            return null;
        }

        if (\strpos($block['element']['text'], '|') !== false && \rtrim($lineArray['text'], ' -:|') === '') {
            $alignments   = [];
            $divider      = $lineArray['text'];
            $divider      = \trim($divider);
            $divider      = \trim($divider, '|');
            $dividerCells = \explode('|', $divider);

            foreach ($dividerCells as $dividerCell) {
                $dividerCell = \trim($dividerCell);

                if ($dividerCell === '') {
                    continue;
                }

                $alignment = null;

                if ($dividerCell[0] === ':') {
                    $alignment = 'left';
                }

                if (\substr($dividerCell, -1) === ':') {
                    $alignment = $alignment === 'left' ? 'center' : 'right';
                }

                $alignments[] = $alignment;
            }

            $headerElements = [];
            $header         = $block['element']['text'];
            $header         = \trim($header);
            $header         = \trim($header, '|');
            $headerCells    = \explode('|', $header);

            foreach ($headerCells as $index => $headerCell) {
                $headerElement = [
                    'name'    => 'th',
                    'text'    => \trim($headerCell),
                    'handler' => 'line',
                ];

                if (isset($alignments[$index])) {
                    $headerElement['attributes'] = [
                        'style' => 'text-align: ' . $alignments[$index] . ';',
                    ];
                }

                $headerElements[] = $headerElement;
            }

            $block = [
                'alignments' => $alignments,
                'identified' => true,
                'element'    => [
                    'name'    => 'table',
                    'handler' => 'elements',
                ],
            ];

            $block['element']['text'][] = [
                'name'    => 'thead',
                'handler' => 'elements',
            ];

            $block['element']['text'][] = [
                'name'    => 'tbody',
                'handler' => 'elements',
                'text'    => [],
            ];

            $block['element']['text'][0]['text'][] = [
                'name'    => 'tr',
                'handler' => 'elements',
                'text'    => $headerElements,
            ];

            return $block;
        }

        return null;
    }

    /**
     * Handle continue table
     *
     * @param array $lineArray Line information
     * @param array $block     Block information
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function blockTableContinue(array $lineArray, array $block) : ?array
    {
        if (isset($block['interrupted'])) {
            return null;
        }

        if ($lineArray['text'][0] === '|' || \strpos($lineArray['text'], '|')) {
            $elements = [];
            $row      = $lineArray['text'];
            $row      = \trim($row);
            $row      = \trim($row, '|');

            \preg_match_all('/(?:(\\\\[|])|[^|`]|`[^`]+`|`)+/', $row, $matches);

            foreach ($matches[0] as $index => $cell) {
                $element = [
                    'name'    => 'td',
                    'handler' => 'line',
                    'text'    => \trim($cell),
                ];

                if (isset($block['alignments'][$index])) {
                    $element['attributes'] = [
                        'style' => 'text-align: ' . $block['alignments'][$index] . ';',
                    ];
                }

                $elements[] = $element;
            }

            $block['element']['text'][1]['text'][] = [
                'name'    => 'tr',
                'handler' => 'elements',
                'text'    => $elements,
            ];

            return $block;
        }
    }

    /**
     * Handle paragraph
     *
     * @param array $lineArray Line information
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected static function paragraph(array $lineArray) : array
    {
        return [
            'element' => [
                'name'    => 'p',
                'text'    => $lineArray['text'],
                'handler' => 'line',
            ],
        ];
    }

    /**
     * Handle a single line
     *
     * @param string $text Line of text
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected static function line(string $text) : string
    {
        $markup = '';

        while ($excerpt = \strpbrk($text, self::$inlineMarkerList)) {
            $marker         = $excerpt[0];
            $markerPosition = \strpos($text, $marker);
            $excerptArray   = ['text' => $excerpt, 'context' => $text];

            foreach (self::$inlineTypes[$marker] as $inlineType) {
                $inline = self::{'inline' . $inlineType}($excerptArray);

                if ($inline === null) {
                    continue;
                }

                if (isset($inline['position']) && $inline['position'] > $markerPosition) {
                    continue;
                }

                if (!isset($inline['position'])) {
                    $inline['position'] = $markerPosition;
                }

                $unmarkedText = (string) \substr($text, 0, $inline['position']);
                $markup      .= self::unmarkedText($unmarkedText);
                $markup      .= isset($inline['markup']) ? $inline['markup'] : self::element($inline['element']);
                $text         = (string) \substr($text, $inline['position'] + $inline['extent']);

                continue 2;
            }

            $unmarkedText = (string) \substr($text, 0, $markerPosition + 1);
            $markup      .= self::unmarkedText($unmarkedText);
            $text         = (string) \substr($text, $markerPosition + 1);
        }

        $markup .= self::unmarkedText($text);

        return $markup;
    }

    /**
     * Handle inline code
     *
     * @param array $excerpt Markdown excerpt
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function inlineCode(array $excerpt) : ?array
    {
        $marker = $excerpt['text'][0];

        if (!\preg_match('/^(' . $marker . '+)[ ]*(.+?)[ ]*(?<!' . $marker . ')\1(?!' . $marker . ')/s', $excerpt['text'], $matches)) {
            return null;
        }

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'name' => 'code',
                'text' => \preg_replace("/[ ]*\n/", ' ', $matches[2]),
            ],
        ];
    }

    /**
     * Handle inline email
     *
     * @param array $excerpt Markdown excerpt
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function inlineEmailTag(array $excerpt) : ?array
    {
        if (\strpos($excerpt['text'], '>') === false || !\preg_match('/^<((mailto:)?\S+?@\S+?)>/i', $excerpt['text'], $matches)) {
            return null;
        }

        $url = $matches[1];

        if (!isset($matches[2])) {
            $url = 'mailto:' . $url;
        }

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'name'       => 'a',
                'text'       => $matches[1],
                'attributes' => [
                    'href' => UriFactory::build($url),
                ],
            ],
        ];
    }

    /**
     * Handle inline emphasis
     *
     * @param array $excerpt Markdown excerpt
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function inlineEmphasis(array $excerpt) : ?array
    {
        if (!isset($excerpt['text'][1])) {
            return null;
        }

        $marker = $excerpt['text'][0];

        if ($excerpt['text'][1] === $marker && isset(self::$strongRegex[$marker]) && \preg_match(self::$strongRegex[$marker], $excerpt['text'], $matches)) {
            $emphasis = 'strong';
        } elseif ($excerpt['text'][1] === $marker && isset(self::$underlineRegex[$marker]) && \preg_match(self::$underlineRegex[$marker], $excerpt['text'], $matches)) {
            $emphasis = 'u';
        } elseif (\preg_match(self::$emRegex[$marker], $excerpt['text'], $matches)) {
            $emphasis = 'em';
        } else {
            return null;
        }

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'name'    => $emphasis,
                'handler' => 'line',
                'text'    => $matches[1],
            ],
        ];
    }

    /**
     * Handle escape of special char
     *
     * @param array $excerpt Markdown excerpt
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function inlineEscapeSequence(array $excerpt) : ?array
    {
        if (!isset($excerpt['text'][1]) || !\in_array($excerpt['text'][1], self::$specialCharacters)) {
            return null;
        }

        return [
            'markup' => $excerpt['text'][1],
            'extent' => 2,
        ];
    }

    /**
     * Handle inline image
     *
     * @param array $excerpt Markdown excerpt
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function inlineImage(array $excerpt) : ?array
    {
        if (!isset($excerpt['text'][1]) || $excerpt['text'][1] !== '[') {
            return null;
        }

        $excerpt['text'] = \substr($excerpt['text'], 1);
        $link            = self::inlineLink($excerpt);

        if ($link === null) {
            return null;
        }

        $inline = [
            'extent'  => $link['extent'] + 1,
            'element' => [
                'name'       => 'img',
                'attributes' => [
                    'src' => UriFactory::build($link['element']['attributes']['href']),
                    'alt' => $link['element']['text'],
                ],
            ],
        ];

        $inline['element']['attributes'] += $link['element']['attributes'];

        unset($inline['element']['attributes']['href']);

        return $inline;
    }

    /**
     * Handle inline link
     *
     * @param array $excerpt Markdown excerpt
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function inlineLink(array $excerpt) : ?array
    {
        $element = [
            'name'       => 'a',
            'handler'    => 'line',
            'text'       => null,
            'attributes' => [
                'href'  => null,
                'title' => null,
            ],
        ];

        $extent    = 0;
        $remainder = $excerpt['text'];

        if (!\preg_match('/\[((?:[^][]++|(?R))*+)\]/', $remainder, $matches)) {
            return null;
        }

        $element['text'] = $matches[1];
        $extent         += \strlen($matches[0]);
        $remainder       = (string) \substr($remainder, $extent);

        if (\preg_match('/^[(]\s*+((?:[^ ()]++|[(][^ )]+[)])++)(?:[ ]+("[^"]*"|\'[^\']*\'))?\s*[)]/', $remainder, $matches)) {
            $element['attributes']['href'] = UriFactory::build($matches[1]);

            if (isset($matches[2])) {
                $element['attributes']['title'] = (string) \substr($matches[2], 1, - 1);
            }

            $extent += \strlen($matches[0]);
        } else {
            if (\preg_match('/^\s*\[(.*?)\]/', $remainder, $matches)) {
                $definition = \strlen($matches[1]) ? $matches[1] : $element['text'];
                $definition = \strtolower($definition);

                $extent += \strlen($matches[0]);
            } else {
                $definition = \strtolower($element['text']);
            }

            if (!isset(self::$definitionData['Reference'][$definition])) {
                return null;
            }

            $def = self::$definitionData['Reference'][$definition];

            $element['attributes']['href']  = UriFactory::build($def['url']);
            $element['attributes']['title'] = $def['title'];
        }

        return [
            'extent'  => $extent,
            'element' => $element,
        ];
    }

    /**
     * Handle special char to html
     *
     * @param array $excerpt Markdown excerpt
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function inlineSpecialCharacter(array $excerpt) : ?array
    {
        if ($excerpt['text'][0] === '&' && !\preg_match('/^&#?\w+;/', $excerpt['text'])) {
            return [
                'markup' => '&amp;',
                'extent' => 1,
            ];
        }

        $specialChar = ['>' => 'gt', '<' => 'lt', '"' => 'quot'];

        if (isset($specialChar[$excerpt['text'][0]])) {
            return [
                'markup' => '&' . $specialChar[$excerpt['text'][0]] . ';',
                'extent' => 1,
            ];
        }
    }

    /**
     * Handle inline strike through
     *
     * @param array $excerpt Markdown excerpt
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function inlineStrikethrough(array $excerpt) : ?array
    {
        if (!isset($excerpt['text'][1])) {
            return null;
        }

        if ($excerpt['text'][1] !== '~' || !\preg_match('/^~~(?=\S)(.+?)(?<=\S)~~/', $excerpt['text'], $matches)) {
            return null;
        }

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'name'    => 'del',
                'text'    => $matches[1],
                'handler' => 'line',
            ],
        ];
    }

    /**
     * Handle inline url
     *
     * @param array $excerpt Markdown excerpt
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function inlineUrl(array $excerpt) : ?array
    {
        if (!isset($excerpt['text'][2]) || $excerpt['text'][2] !== '/') {
            return null;
        }

        if (!\preg_match('/\bhttps?:[\/]{2}[^\s<]+\b\/*/ui', $excerpt['context'], $matches, \PREG_OFFSET_CAPTURE)) {
            return null;
        }

        return [
            'extent'   => \strlen($matches[0][0]),
            'position' => $matches[0][1],
            'element'  => [
                'name'       => 'a',
                'text'       => $matches[0][0],
                'attributes' => [
                    'href' => UriFactory::build($matches[0][0]),
                ],
            ],
        ];
    }

    /**
     * Handle inline url
     *
     * @param array $excerpt Markdown excerpt
     *
     * @return null|array
     *
     * @since 1.0.0
     */
    protected static function inlineUrlTag(array $excerpt) : ?array
    {
        if (\strpos($excerpt['text'], '>') === false || !\preg_match('/^<(\w+:\/{2}[^ >]+)>/i', $excerpt['text'], $matches)) {
            return null;
        }

        return [
            'extent'  => \strlen($matches[0]),
            'element' => [
                'name'       => 'a',
                'text'       => $matches[1],
                'attributes' => [
                    'href' => UriFactory::build($matches[1]),
                ],
            ],
        ];
    }

    /**
     * Clean up normal text
     *
     * @param string $text Normal text
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected static function unmarkedText(string $text) : string
    {
        $text = \preg_replace('/(?:[ ][ ]+|[ ]*\\\\)\n/', "<br />\n", $text);
        $text = \str_replace(" \n", "\n", $text);

        return $text;
    }

    /**
     * Handle general html element
     *
     * @param array $element Html element
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected static function element(array $element) : string
    {
        $element = self::sanitizeAndBuildElement($element);
        $markup  = '<' . $element['name'];

        if (isset($element['attributes'])) {
            foreach ($element['attributes'] as $name => $value) {
                if ($value === null) {
                    continue;
                }

                $markup .= ' ' . $name . '="' . self::escape($value) . '"';
            }
        }

        if (isset($element['text'])) {
            $markup .= '>';
            $markup .= isset($element['handler']) ? self::{$element['handler']}($element['text']) : self::escape($element['text'], true);
            $markup .= '</' . $element['name'] . '>';
        } else {
            $markup .= ' />';
        }

        return $markup;
    }

    /**
     * Handle an array of elements
     *
     * @param array $elements Elements
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected static function elements(array $elements) : string
    {
        $markup = '';

        foreach ($elements as $element) {
            $markup .= "\n" . self::element($element);
        }

        $markup .= "\n";

        return $markup;
    }

    /**
     * Remove blocks
     *
     * @param array $lines Lines
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected static function li(array $lines) : string
    {
        $markup        = self::lines($lines);
        $trimmedMarkup = \trim($markup);

        if (!\in_array('', $lines) && \substr($trimmedMarkup, 0, 3) === '<p>') {
            $markup   = $trimmedMarkup;
            $markup   = (string) \substr($markup, 3);
            $position = \strpos($markup, '</p>');
            $markup   = \substr_replace($markup, '', $position, 4);
        }

        return $markup;
    }

    /**
     * Sanitize an element
     *
     * @param array $element Element to sanitize
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected static function sanitizeAndBuildElement(array $element) : array
    {
        $safeUrlNameToAtt = [
            'a'   => 'href',
            'img' => 'src',
        ];

        if (isset($safeUrlNameToAtt[$element['name']])) {
            $element = self::filterUnsafeUrlInAttribute($element, $safeUrlNameToAtt[$element['name']]);
        }

        if (!empty($element['attributes'])) {
            foreach ($element['attributes'] as $att => $val) {
                if (!\preg_match('/^[a-zA-Z0-9][a-zA-Z0-9-_]*+$/', $att)) {
                    unset($element['attributes'][$att]);
                } elseif (self::striAtStart($att, 'on')) {
                    unset($element['attributes'][$att]);
                }
            }
        }

        return $element;
    }

    /**
     * Replace unsafe url
     *
     * @param array  $element   Element to sanitize
     * @param string $attribute Element attribute
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected static function filterUnsafeUrlInAttribute(array $element, string $attribute) : array
    {
        foreach (self::$safeLinksWhitelist as $scheme) {
            if (self::striAtStart($element['attributes'][$attribute], $scheme)) {
                return $element;
            }
        }

        $element['attributes'][$attribute] = \str_replace(':', '%3A', $element['attributes'][$attribute]);

        return $element;
    }

    /**
     * Escape html elements
     *
     * @param string $text        Text to escape
     * @param bool   $allowQuotes Are quotes allowed
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected static function escape(string $text, bool $allowQuotes = false) : string
    {
        return \htmlspecialchars($text, $allowQuotes ? \ENT_NOQUOTES : \ENT_QUOTES, 'UTF-8');
    }

    /**
     * Check if string starts with
     *
     * @param string $string Text to check against
     * @param string $needle Needle to check
     *
     * @return bool
     *
     * @since 1.0.0
     */
    protected static function striAtStart(string $string, string $needle) : bool
    {
        $length = \strlen($needle);

        if ($length > \strlen($string)) {
            return false;
        }

        return \strtolower((string) \substr($string, 0, $length)) === \strtolower($needle);
    }
}
