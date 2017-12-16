<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @license    Original license Emanuil Rusev, erusev.com (MIT)
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types = 1);

namespace phpOMS\Utils\Parser\Markdown;

/**
 * Markdown parser class.
 *
 * @category   Framework
 * @package    phpOMS\Utils\Parser
 * @license    OMS License 1.0
 * @license    Original license Emanuil Rusev, erusev.com (MIT)
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class Markdown
{
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
        '<' => ['Comment', 'Markup'],
        '=' => ['SetextHeader'],
        '>' => ['Quote'],
        '[' => ['Reference'],
        '_' => ['Rule'],
        '`' => ['FencedCode'],
        '|' => ['Table'],
        '~' => ['FencedCode'],
    ];

    protected static $unmarkedBlockTypes = [
        'Code',
    ];

    protected static $specialCharacters = [
        '\\', '`', '*', '_', '{', '}', '[', ']', '(', ')', '>', '#', '+', '-', '.', '!', '|',
    ];

    protected static $strongRegex = [
        '*' => '/^[*]{2}((?:\\\\\*|[^*]|[*][^*]*[*])+?)[*]{2}(?![*])/s',
        '_' => '/^__((?:\\\\_|[^_]|_[^_]*_)+?)__(?!_)/us',
    ];

    protected static $emRegex = [
        '*' => '/^[*]((?:\\\\\*|[^*]|[*][*][^*]+?[*][*])+?)[*](?![*])/s',
        '_' => '/^_((?:\\\\_|[^_]|__[^_]*__)+?)_(?!_)\b/us',
    ];

    protected static $regexHtmlAttribute = '[a-zA-Z_:][\w:.-]*(?:\s*=\s*(?:[^"\'=<>`\s]+|"[^"]*"|\'[^\']*\'))?';

    protected static $voidElements = [
        'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source',
    ];

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

    protected static $inlineTypes = [
        '"' => ['SpecialCharacter'],
        '!' => ['Image'],
        '&' => ['SpecialCharacter'],
        '*' => ['Emphasis'],
        ':' => ['Url'],
        '<' => ['UrlTag', 'EmailTag', 'Markup', 'SpecialCharacter'],
        '>' => ['SpecialCharacter'],
        '[' => ['Link'],
        '_' => ['Emphasis'],
        '`' => ['Code'],
        '~' => ['Strikethrough'],
        '\\' => ['EscapeSequence'],
    ];

    protected static $inlineMarkerList = '!"*_&[:<>`~\\';

    private static $continuable = [
        'Code', 'Comment', 'FencedCode', 'List', 'Quote', 'Markup', 'Table'
    ];

    private static $completable = [
        'Code', 'FencedCode'
    ];

    private static $definitionData = [];

    public static function parse(string $text) : string
    {
        self::$definitionData = [];
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = trim($text, "\n");
        $lines = explode("\n", $text);
        $markup = self::lines($lines);

        return trim($markup, "\n");
    }

    protected static function lines(array $lines) : string
    {
        $currentBlock = null;

        foreach ($lines as $line) {
            if (chop($line) === '') {
                if (isset($currentBlock)) {
                    $currentBlock['interrupted'] = true;
                }

                continue;
            }

            if (strpos($line, "\t") !== false) {
                $parts = explode("\t", $line);
                $line = $parts[0];

                unset($parts[0]);

                foreach ($parts as $part) {
                    $shortage = 4 - mb_strlen($line, 'utf-8') % 4;

                    $line .= str_repeat(' ', $shortage);
                    $line .= $part;
                }
            }

            $indent = 0;
            while (isset($line[$indent]) && $line[$indent] === ' ') {
                $indent ++;
            }

            $text = $indent > 0 ? substr($line, $indent) : $line;
            $lineArray = ['body' => $line, 'indent' => $indent, 'text' => $text];

            if (isset($currentBlock['continuable'])) {
                $block = self::{'block' . $currentBlock['type'] . 'Continue'}($lineArray, $currentBlock);

                if (isset($block)) {
                    $currentBlock = $block;

                    continue;
                } elseif (in_array($currentBlock['type'], self::$completable)) {
                    $currentBlock = self::{'block' . $currentBlock['type'] . 'Complete'}($currentBlock);
                }
            }

            $marker = $text[0];
            $blockTypes = self::$unmarkedBlockTypes;

            if (isset(self::$blockTypes[$marker])) {
                foreach (self::$blockTypes[$marker] as $blockType) {
                    $blockTypes[] = $blockType;
                }
            }

            foreach ($blockTypes as $blockType) {
                $block = self::{'block' . $blockType}($lineArray, $currentBlock);

                if (isset($block)) {
                    $block['type'] = $blockType;

                    if (!isset($block['identified'])) {
                        $blocks[] = $currentBlock;

                        $block['identified'] = true;
                    }

                    if (in_array($blockType, self::$continuable)) {
                        $block['continuable'] = true;
                    }

                    $currentBlock = $block;

                    continue 2;
                }
            }

            if (isset($currentBlock) && !isset($currentBlock['type']) && !isset($currentBlock['interrupted'])) {
                $currentBlock['element']['text'] .= "\n" . $text;
            } else {
                $blocks[] = $currentBlock;
                $currentBlock = self::paragraph($lineArray);
                $currentBlock['identified'] = true;
            }
        }

        if (isset($currentBlock['continuable']) && in_array($currentBlock['type'], self::$completable)) {
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

    protected static function blockCode(array $lineArray, array $block = null) /* : ?array */
    {
        if (isset($block) && !isset($block['type']) && !isset($block['interrupted'])) {
            return;
        }

        if ($lineArray['indent'] < 4) {
            return;
        }

        $text = substr($lineArray['body'], 4);
        $block = [
            'element' => [
                'name' => 'pre',
                'handler' => 'element',
                'text' => [
                    'name' => 'code',
                    'text' => $text,
                ],
            ],
        ];

        return $block;
    }

    protected static function blockCodeContinue(array $lineArray, array $block) /* : ?array */
    {
        if ($lineArray['indent'] < 4) {
            return;
        }

        if (isset($block['interrupted'])) {
            $block['element']['text']['text'] .= "\n";

            unset($block['interrupted']);
        }

        $block['element']['text']['text'] .= "\n";
        $text = substr($lineArray['body'], 4);
        $block['element']['text']['text'] .= $text;

        return $block;
    }

    protected static function blockCodeComplete(array $block) : array
    {
        $text = $block['element']['text']['text'];
        $text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');
        $block['element']['text']['text'] = $text;

        return $block;
    }

    protected static function blockComment(array $lineArray) /* : ?array */
    {
        if (
            isset($lineArray['text'][3]) 
            && $lineArray['text'][3] === '-' 
            && $lineArray['text'][2] === '-' 
            && $lineArray['text'][1] === '!'
        ) {
            $block = ['markup' => $lineArray['body']];

            if (preg_match('/-->$/', $lineArray['text'])) {
                $block['closed'] = true;
            }

            return $block;
        }
    }

    protected static function blockCommentContinue(array $lineArray, array $block) /* : ?array */
    {
        if (isset($block['closed'])) {
            return;
        }

        $block['markup'] .= "\n" . $lineArray['body'];

        if (preg_match('/-->$/', $lineArray['text'])) {
            $block['closed'] = true;
        }

        return $block;
    }

    protected static function blockFencedCode(array $lineArray) /* : ?array */
    {
        if (!preg_match('/^[' . $lineArray['text'][0] . ']{3,}[ ]*([\w-]+)?[ ]*$/', $lineArray['text'], $matches)) {
            return;
        }

        $elementArray = [
            'name' => 'code',
            'text' => '',
        ];

        if (isset($matches[1])) {
            $class = 'language-' . $matches[1];

            $elementArray['attributes'] = [
                'class' => $class,
            ];
        }

        $block = [
            'char' => $lineArray['text'][0],
            'element' => [
                'name' => 'pre',
                'handler' => 'element',
                'text' => $elementArray,
            ]
        ];

        return $block;
    }

    protected static function blockFencedCodeContinue(array $lineArray, array $block) /* : ?array */
    {
        if (isset($block['complete'])) {
            return;
        }

        if (isset($block['interrupted'])) {
            $block['element']['text']['text'] .= "\n";

            unset($block['interrupted']);
        }

        if (preg_match('/^' . $block['char'] . '{3,}[ ]*$/', $lineArray['text'])) {
            $block['element']['text']['text'] = substr($block['element']['text']['text'], 1);
            $block['complete'] = true;

            return $block;
        }

        $block['element']['text']['text'] .= "\n" . $lineArray['body'];

        return $block;
    }

    protected static function blockFencedCodeComplete(array $block) : array
    {
        $text = $block['element']['text']['text'];
        $text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');
        $block['element']['text']['text'] = $text;

        return $block;
    }

    protected static function blockHeader(array $lineArray) /* : ?array */
    {
        if (!isset($lineArray['text'][1])) {
            return;
        }

        $level = 1;

        while (isset($lineArray['text'][$level]) && $lineArray['text'][$level] === '#') {
            $level ++;
        }

        if ($level > 6) {
            return;
        }

        $text = trim($lineArray['text'], '# ');
        $block = [
            'element' => [
                'name' => 'h' . min(6, $level),
                'text' => $text,
                'handler' => 'line',
            ],
        ];

        return $block;
    }

    protected static function blockList(array $lineArray) /* : ?array */
    {
        list($name, $pattern) = $lineArray['text'][0] <= '-' ? ['ul', '[*+-]'] : ['ol', '[0-9]+[.]'];

        if (!preg_match('/^(' . $pattern . '[ ]+)(.*)/', $lineArray['text'], $matches)) {
            return;
        }

        $block = [
            'indent' => $lineArray['indent'],
            'pattern' => $pattern,
            'element' => [
                'name' => $name,
                'handler' => 'elements',
            ],
        ];

        if($name === 'ol') {
            $listStart = stristr($matches[0], '.', true);
            
            /*
            if($listStart !== '1') {
                $block['element']['attributes'] = ['start' => $listStart];
            }*/
        }

        $block['li'] = [
            'name' => 'li',
            'handler' => 'li',
            'text' => [
                $matches[2],
            ],
        ];

        $block['element']['text'][] = & $block['li'];

        return $block;
    }

    protected static function blockListContinue(array $lineArray, array $block) /* : ?array */
    {
        if ($block['indent'] === $lineArray['indent'] && preg_match('/^' . $block['pattern'] . '(?:[ ]+(.*)|$)/', $lineArray['text'], $matches)) {
            if (isset($block['interrupted'])) {
                $block['li']['text'][] = '';

                unset($block['interrupted']);
            }

            unset($block['li']);

            $text = isset($matches[1]) ? $matches[1] : '';
            $block['li'] = [
                'name' => 'li',
                'handler' => 'li',
                'text' => [
                    $text,
                ],
            ];

            $block['element']['text'][] = & $block['li'];

            return $block;
        }

        if ($lineArray['text'][0] === '[' && self::blockReference($lineArray)) {
            return $block;
        }

        if (!isset($block['interrupted'])) {
            $text = preg_replace('/^[ ]{0,4}/', '', $lineArray['body']);
            $block['li']['text'][] = $text;

            return $block;
        }

        if ($lineArray['indent'] > 0) {
            $block['li']['text'][] = '';
            $text = preg_replace('/^[ ]{0,4}/', '', $lineArray['body']);
            $block['li']['text'][] = $text;

            unset($block['interrupted']);

            return $block;
        }
    }

    protected static function blockQuote(array $lineArray) /* : ?array */
    {
        if (!preg_match('/^>[ ]?(.*)/', $lineArray['text'], $matches)) {
            return;
        }

        $block = [
            'element' => [
                'name' => 'blockquote',
                'handler' => 'lines',
                'text' => (array) $matches[1],
            ],
        ];

        return $block;
    }

    protected static function blockQuoteContinue(array $lineArray, array $block) /* : ?array */
    {
        if ($lineArray['text'][0] === '>' && preg_match('/^>[ ]?(.*)/', $lineArray['text'], $matches)) {
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
    }

    protected static function blockRule(array $lineArray) /* : ?array */
    {
        if (!preg_match('/^([' . $lineArray['text'][0] . '])([ ]*\1){2,}[ ]*$/', $lineArray['text'])) {
            return;
        }

        $block = [
            'element' => [
                'name' => 'hr'
            ],
        ];

        return $block;
    }

    protected static function blockSetextHeader(array $lineArray, array $block = null) /* : ?array */
    {
        if (!isset($block) || isset($block['type']) || isset($block['interrupted'])) {
            return;
        }

        if (chop($lineArray['text'], $lineArray['text'][0]) !== '') {
            return;
        }

        $block['element']['name'] = $lineArray['text'][0] === '=' ? 'h1' : 'h2';

        return $block;
    }

    protected static function blockMarkup(array $lineArray) /* : ?array */
    {
        if (!preg_match('/^<(\w[\w-]*)(?:[ ]*' . self::$regexHtmlAttribute . ')*[ ]*(\/)?>/', $lineArray['text'], $matches)) {
            return;
        }
            
        $element = strtolower($matches[1]);

        if (in_array($element, self::$textLevelElements)) {
            return;
        }

        $block = [
            'name' => $matches[1],
            'depth' => 0,
            'markup' => $lineArray['text'],
        ];

        $length = strlen($matches[0]);
        $remainder = substr($lineArray['text'], $length);

        if (trim($remainder) === '') {
            if (isset($matches[2]) || in_array($matches[1], self::$voidElements)) {
                $block['closed'] = true;
                $block['void'] = true;
            }
        } else {
            if (isset($matches[2]) || in_array($matches[1], self::$voidElements)) {
                return;
            }

            if (preg_match('/<\/' . $matches[1] . '>[ ]*$/i', $remainder)) {
                $block['closed'] = true;
            }
        }

        return $block;
    }

    protected static function blockMarkupContinue(array $lineArray, array $block) /* : ?array */
    {
        if (isset($block['closed'])) {
            return;
        }

        if (preg_match('/^<' . $block['name'] . '(?:[ ]*' . self::$regexHtmlAttribute . ')*[ ]*>/i', $lineArray['text'])) {
            $block['depth']++;
        }

        if (preg_match('/(.*?)<\/' . $block['name'] . '>[ ]*$/i', $lineArray['text'], $matches)) {
            if ($block['depth'] > 0) {
                $block['depth']--;
            } else {
                $block['closed'] = true;
            }
        }

        if (isset($block['interrupted'])) {
            $block['markup'] .= "\n";

            unset($block['interrupted']);
        }

        $block['markup'] .= "\n".$lineArray['body'];

        return $block;
    }

    protected static function blockReference(array $lineArray) /* : ?array */
    {
        if (!preg_match('/^\[(.+?)\]:[ ]*<?(\S+?)>?(?:[ ]+["\'(](.+)["\')])?[ ]*$/', $lineArray['text'], $matches)) {
            return;
        }

        $id = strtolower($matches[1]);
        $Data = [
            'url' => $matches[2],
            'title' => null,
        ];

        if (isset($matches[3])) {
            $Data['title'] = $matches[3];
        }

        self::$definitionData['Reference'][$id] = $Data;

        $block = ['hidden' => true];

        return $block;
    }

    protected static function blockTable($lineArray, array $block = null) /* : ?array */
    {
        if (!isset($block) || isset($block['type']) || isset($block['interrupted'])) {
            return;
        }

        if (strpos($block['element']['text'], '|') !== false && chop($lineArray['text'], ' -:|') === '') {
            $alignments = [];
            $divider = $lineArray['text'];
            $divider = trim($divider);
            $divider = trim($divider, '|');
            $dividerCells = explode('|', $divider);

            foreach ($dividerCells as $dividerCell) {
                $dividerCell = trim($dividerCell);

                if ($dividerCell === '') {
                    continue;
                }

                $alignment = null;

                if ($dividerCell[0] === ':') {
                    $alignment = 'left';
                }

                if (substr($dividerCell, - 1) === ':') {
                    $alignment = $alignment === 'left' ? 'center' : 'right';
                }

                $alignments[] = $alignment;
            }

            $HeaderElements = [];
            $header = $block['element']['text'];
            $header = trim($header);
            $header = trim($header, '|');
            $headerCells = explode('|', $header);

            foreach ($headerCells as $index => $headerCell) {
                $headerCell = trim($headerCell);
                $HeaderElement = [
                    'name' => 'th',
                    'text' => $headerCell,
                    'handler' => 'line',
                ];

                if (isset($alignments[$index])) {
                    $alignment = $alignments[$index];
                    $HeaderElement['attributes'] = [
                        'style' => 'text-align: ' . $alignment . ';',
                    ];
                }

                $HeaderElements[] = $HeaderElement;
            }

            $block = [
                'alignments' => $alignments,
                'identified' => true,
                'element' => [
                    'name' => 'table',
                    'handler' => 'elements',
                ],
            ];

            $block['element']['text'][] = [
                'name' => 'thead',
                'handler' => 'elements',
            ];

            $block['element']['text'][] = [
                'name' => 'tbody',
                'handler' => 'elements',
                'text' => [],
            ];

            $block['element']['text'][0]['text'][] = [
                'name' => 'tr',
                'handler' => 'elements',
                'text' => $HeaderElements,
            ];

            return $block;
        }
    }

    protected static function blockTableContinue(array $lineArray, array $block) /* : ?array */
    {
        if (isset($block['interrupted'])) {
            return;
        }

        if ($lineArray['text'][0] === '|' || strpos($lineArray['text'], '|')) {
            $elements = [];
            $row = $lineArray['text'];
            $row = trim($row);
            $row = trim($row, '|');

            preg_match_all('/(?:(\\\\[|])|[^|`]|`[^`]+`|`)+/', $row, $matches);

            foreach ($matches[0] as $index => $cell) {
                $cell = trim($cell);
                $element = [
                    'name' => 'td',
                    'handler' => 'line',
                    'text' => $cell,
                ];

                if (isset($block['alignments'][$index])) {
                    $element['attributes'] = [
                        'style' => 'text-align: ' . $block['alignments'][$index] . ';',
                    ];
                }

                $elements[] = $element;
            }

            $element = [
                'name' => 'tr',
                'handler' => 'elements',
                'text' => $elements,
            ];
            $block['element']['text'][1]['text'][] = $element;

            return $block;
        }
    }

    protected static function paragraph(array $lineArray) : array
    {
        $block = [
            'element' => [
                'name' => 'p',
                'text' => $lineArray['text'],
                'handler' => 'line',
            ],
        ];

        return $block;
    }

    protected static function line(string $text) : string
    {
        $markup = '';

        while ($excerpt = strpbrk($text, self::$inlineMarkerList)) {
            $marker = $excerpt[0];
            $markerPosition = strpos($text, $marker);
            $Excerpt = ['text' => $excerpt, 'context' => $text];

            foreach (self::$inlineTypes[$marker] as $inlineType) {
                $inline = self::{'inline' . $inlineType}($Excerpt);

                if (!isset($inline)) {
                    continue;
                }

                if (isset($inline['position']) && $inline['position'] > $markerPosition) {
                    continue;
                }

                if (!isset($inline['position'])) {
                    $inline['position'] = $markerPosition;
                }

                $unmarkedText = substr($text, 0, $inline['position']);
                $markup .= self::unmarkedText($unmarkedText);
                $markup .= isset($inline['markup']) ? $inline['markup'] : self::element($inline['element']);
                $text = substr($text, $inline['position'] + $inline['extent']);

                continue 2;
            }

            $unmarkedText = substr($text, 0, $markerPosition + 1);
            $markup .= self::unmarkedText($unmarkedText);
            $text = substr($text, $markerPosition + 1);
        }

        $markup .= self::unmarkedText($text);

        return $markup;
    }

    protected static function inlineCode(array $excerpt) /* : ?array */
    {
        $marker = $excerpt['text'][0];

        if (!preg_match('/^(' . $marker . '+)[ ]*(.+?)[ ]*(?<!' . $marker . ')\1(?!' . $marker . ')/s', $excerpt['text'], $matches)) {
            return;
        }

        $text = $matches[2];
        $text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');
        $text = preg_replace("/[ ]*\n/", ' ', $text);

        return [
            'extent' => strlen($matches[0]),
            'element' => [
                'name' => 'code',
                'text' => $text,
            ],
        ];
    }

    protected static function inlineEmailTag(array $excerpt) /* : ?array */
    {
        if (strpos($excerpt['text'], '>') === false || !preg_match('/^<((mailto:)?\S+?@\S+?)>/i', $excerpt['text'], $matches)) {
            return;
        }
            
        $url = $matches[1];

        if (!isset($matches[2])) {
            $url = 'mailto:' . $url;
        }

        return [
            'extent' => strlen($matches[0]),
            'element' => [
                'name' => 'a',
                'text' => $matches[1],
                'attributes' => [
                    'href' => $url,
                ],
            ],
        ];
    }

    protected static function inlineEmphasis(array $excerpt) /* : ?array */
    {
        if (!isset($excerpt['text'][1])) {
            return;
        }

        $marker = $excerpt['text'][0];

        if ($excerpt['text'][1] === $marker && preg_match(self::$strongRegex[$marker], $excerpt['text'], $matches)) {
            $emphasis = 'strong';
        } elseif (preg_match(self::$emRegex[$marker], $excerpt['text'], $matches)) {
            $emphasis = 'em';
        } else {
            return;
        }

        return [
            'extent' => strlen($matches[0]),
            'element' => [
                'name' => $emphasis,
                'handler' => 'line',
                'text' => $matches[1],
            ],
        ];
    }

    protected static function inlineEscapeSequence(array $excerpt) /* : ?array */
    {
        if (!isset($excerpt['text'][1]) || !in_array($excerpt['text'][1], self::$specialCharacters)) {
            return;
        }

        return [
            'markup' => $excerpt['text'][1],
            'extent' => 2,
        ];
    }

    protected static function inlineImage(array $excerpt) /* : ?array */
    {
        if (!isset($excerpt['text'][1]) || $excerpt['text'][1] !== '[') {
            return;
        }

        $excerpt['text']= substr($excerpt['text'], 1);
        $link = self::inlineLink($excerpt);

        if (!isset($link)) {
            return;
        }

        $inline = [
            'extent' => $link['extent'] + 1,
            'element' => [
                'name' => 'img',
                'attributes' => [
                    'src' => $link['element']['attributes']['href'],
                    'alt' => $link['element']['text'],
                ],
            ],
        ];

        $inline['element']['attributes'] += $link['element']['attributes'];

        unset($inline['element']['attributes']['href']);

        return $inline;
    }

    protected static function inlineLink(array $excerpt) /* : ?array */
    {
        $element = [
            'name' => 'a',
            'handler' => 'line',
            'text' => null,
            'attributes' => [
                'href' => null,
                'title' => null,
            ],
        ];
        $extent = 0;
        $remainder = $excerpt['text'];

        if (!preg_match('/\[((?:[^][]++|(?R))*+)\]/', $remainder, $matches)) {
            return;
        }

        $element['text'] = $matches[1];
        $extent += strlen($matches[0]);
        $remainder = substr($remainder, $extent);

        if (preg_match('/^[(]\s*+((?:[^ ()]++|[(][^ )]+[)])++)(?:[ ]+("[^"]*"|\'[^\']*\'))?\s*[)]/', $remainder, $matches)) {
            $element['attributes']['href'] = $matches[1];

            if (isset($matches[2])) {
                $element['attributes']['title'] = substr($matches[2], 1, - 1);
            }

            $extent += strlen($matches[0]);
        } else {
            if (preg_match('/^\s*\[(.*?)\]/', $remainder, $matches)) {
                $definition = strlen($matches[1]) ? $matches[1] : $element['text'];
                $definition = strtolower($definition);
                $extent += strlen($matches[0]);
            } else {
                $definition = strtolower($element['text']);
            }

            if (!isset(self::$definitionData['Reference'][$definition])) {
                return;
            }

            $def = self::$definitionData['Reference'][$definition];
            $element['attributes']['href'] = $def['url'];
            $element['attributes']['title'] = $def['title'];
        }

        $element['attributes']['href'] = str_replace(['&', '<'], ['&amp;', '&lt;'], $element['attributes']['href']);

        return [
            'extent' => $extent,
            'element' => $element,
        ];
    }

    protected static function inlineMarkup(array $excerpt) /* : ?array */
    {
        if (strpos($excerpt['text'], '>') === false) {
            return;
        }

        if ($excerpt['text'][1] === '/' && preg_match('/^<\/\w[\w-]*[ ]*>/s', $excerpt['text'], $matches)) {
            return [
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            ];
        }

        if ($excerpt['text'][1] === '!' && preg_match('/^<!---?[^>-](?:-?[^-])*-->/s', $excerpt['text'], $matches)) {
            return [
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            ];
        }

        if ($excerpt['text'][1] !== ' ' && preg_match('/^<\w[\w-]*(?:[ ]*' . self::$regexHtmlAttribute . ')*[ ]*\/?>/s', $excerpt['text'], $matches)) {
            return [
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            ];
        }
    }

    protected static function inlineSpecialCharacter(array $excerpt) /* : ?array */
    {
        if ($excerpt['text'][0] === '&' && ! preg_match('/^&#?\w+;/', $excerpt['text'])) {
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

    protected static function inlineStrikethrough(array $excerpt) /* : ?array */
    {
        if (!isset($excerpt['text'][1])) {
            return;
        }

        if ($excerpt['text'][1] !== '~' || !preg_match('/^~~(?=\S)(.+?)(?<=\S)~~/', $excerpt['text'], $matches)) {
            return;
        }

        return [
            'extent' => strlen($matches[0]),
            'element' => [
                'name' => 'del',
                'text' => $matches[1],
                'handler' => 'line',
            ],
        ];
    }

    protected static function inlineUrl(array $excerpt) /* : ?array */
    {
        if (!isset($excerpt['text'][2]) || $excerpt['text'][2] !== '/') {
            return;
        }

        if (!preg_match('/\bhttps?:[\/]{2}[^\s<]+\b\/*/ui', $excerpt['context'], $matches, PREG_OFFSET_CAPTURE)) {
            return;
        }

        return [
            'extent' => strlen($matches[0][0]),
            'position' => $matches[0][1],
            'element' => [
                'name' => 'a',
                'text' => $matches[0][0],
                'attributes' => [
                    'href' => $matches[0][0],
                ],
            ],
        ];
    }

    protected static function inlineUrlTag(array $excerpt) /* : ?array */
    {
        if (strpos($excerpt['text'], '>') === false || !preg_match('/^<(\w+:\/{2}[^ >]+)>/i', $excerpt['text'], $matches)) {
            return;
        }

        $url = str_replace(['&', '<'], ['&amp;', '&lt;'], $matches[1]);

        return [
            'extent' => strlen($matches[0]),
            'element' => [
                'name' => 'a',
                'text' => $url,
                'attributes' => [
                    'href' => $url,
                ],
            ],
        ];
    }

    protected static function unmarkedText(string $text) : string
    {
        $text = preg_replace('/(?:[ ][ ]+|[ ]*\\\\)\n/', "<br />\n", $text);
        $text = str_replace(" \n", "\n", $text);

        return $text;
    }

    protected static function element(array $element) : string
    {
        $markup = '<' . $element['name'];

        if (isset($element['attributes'])) {
            foreach ($element['attributes'] as $name => $value) {
                if ($value === null) {
                    continue;
                }

                $markup .= ' ' . $name . '="' . $value . '"';
            }
        }

        if (isset($element['text'])) {
            $markup .= '>';
            $markup .= isset($element['handler']) ? self::{$element['handler']}($element['text']) : $element['text'];
            $markup .= '</' . $element['name'] . '>';
        } else {
            $markup .= ' />';
        }

        return $markup;
    }

    protected static function elements(array $elements) : string
    {
        $markup = '';

        foreach ($elements as $element) {
            $markup .= "\n" . self::element($element);
        }

        $markup .= "\n";

        return $markup;
    }

    protected static function li($lines) : string
    {
        $markup = self::lines($lines);
        $trimmedMarkup = trim($markup);

        if (!in_array('', $lines) && substr($trimmedMarkup, 0, 3) === '<p>') {
            $markup = $trimmedMarkup;
            $markup = substr($markup, 3);
            $position = strpos($markup, "</p>");
            $markup = substr_replace($markup, '', $position, 4);
        }

        return $markup;
    }
}