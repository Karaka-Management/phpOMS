<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Utils\Parser\Markdown;

// TODO: implement own version
class Markdown
{
    const version = '1.5.4';

    private        $breaksEnabled;
    private        $markupEscaped;
    private        $urlsLinked         = true;
    private        $blockTypes         = [
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
    private        $unmarkedBlockTypes = [
        'Code',
    ];
    private        $inlineTypes        = [
        '"'  => ['SpecialCharacter'],
        '!'  => ['Image'],
        '&'  => ['SpecialCharacter'],
        '*'  => ['Emphasis'],
        ':'  => ['Url'],
        '<'  => ['UrlTag', 'EmailTag', 'Markup', 'SpecialCharacter'],
        '>'  => ['SpecialCharacter'],
        '['  => ['Link'],
        '_'  => ['Emphasis'],
        '`'  => ['Code'],
        '~'  => ['Strikethrough'],
        '\\' => ['EscapeSequence'],
    ];
    private        $inlineMarkerList   = '!"*_&[:<>`~\\';
    private        $definitionData;
    private        $specialCharacters  = [
        '\\', '`', '*', '_', '{', '}', '[', ']', '(', ')', '>', '#', '+', '-', '.', '!', '|',
    ];
    private        $strongRegex        = [
        '*' => '/^[*]{2}((?:\\\\\*|[^*]|[*][^*]*[*])+?)[*]{2}(?![*])/s',
        '_' => '/^__((?:\\\\_|[^_]|_[^_]*_)+?)__?!_)/us',
    ];
    private        $emRegex            = [
        '*' => '/^[*]((?:\\\\\*|[^*]|[*][*][^*]+?[*][*])+?)[*](?![*])/s',
        '_' => '/^_((?:\\\\_|[^_]|__[^_]*__)+?)_(?!_)\b/us',
    ];
    private        $regexHtmlAttribute = '[a-zA-Z_:][\w:.-]*(?:\s*=\s*(?:[^"\'=<>`\s]+|"[^"]*"|\'[^\']*\'))?';
    private        $voidElements       = [
        'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source',
    ];
    private        $textLevelElements  = [
        'a', 'br', 'bdo', 'abbr', 'blink', 'nextid', 'acronym', 'basefont',
        'b', 'em', 'big', 'cite', 'small', 'spacer', 'listing',
        'i', 'rp', 'del', 'code', 'strike', 'marquee',
        'q', 'rt', 'ins', 'font', 'strong',
        's', 'tt', 'sub', 'mark',
        'u', 'xm', 'sup', 'nobr',
        'var', 'ruby',
        'wbr', 'span',
        'time',
    ];
    private static $instances          = [];

    public static function instance($name = 'default')
    {
        if (isset(self::$instances[$name])) {
            return self::$instances[$name];
        }
        $instance               = new static();
        self::$instances[$name] = $instance;

        return $instance;
    }

    public function setBreaksEnabled($breaksEnabled)
    {
        $this->breaksEnabled = $breaksEnabled;

        return $this;
    }

    public function setMarkupEscaped($markupEscaped)
    {
        $this->markupEscaped = $markupEscaped;

        return $this;
    }

    public function setUrlsLinked($urlsLinked)
    {
        $this->urlsLinked = $urlsLinked;

        return $this;
    }

    public function line($text)
    {
        $markup = '';
        while ($excerpt = strpbrk($text, $this->inlineMarkerList)) {
            $marker         = $excerpt[0];
            $markerPosition = strpos($text, $marker);
            $excerpt        = ['text' => $excerpt, 'context' => $text];
            foreach ($this->inlineTypes[$marker] as $inlineType) {
                $inline = $this->{'inline' . $inlineType}($excerpt);
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

                $markup .= $this->unmarkedText($unmarkedText);
                $markup .= isset($inline['markup']) ? $inline['markup'] : $this->element($inline['element']);
                $text = substr($text, $inline['position'] + $inline['extent']);
                continue 2;
            }

            $unmarkedText = substr($text, 0, $markerPosition + 1);
            $markup .= $this->unmarkedText($unmarkedText);
            $text = substr($text, $markerPosition + 1);
        }
        $markup .= $this->unmarkedText($text);

        return $markup;
    }

    private function unmarkedText($text)
    {
        if ($this->breaksEnabled) {
            $text = preg_replace('/[ ]*\n/', "<br />\n", $text);
        } else {
            $text = preg_replace('/(?:[ ][ ]+|[ ]*\\\\)\n/', "<br />\n", $text);
            $text = str_replace(" \n", "\n", $text);
        }

        return $text;
    }

    private function element(array $element)
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
            if (isset($element['handler'])) {
                $markup .= $this->{$element['handler']}($element['text']);
            } else {
                $markup .= $element['text'];
            }
            $markup .= '</' . $element['name'] . '>';
        } else {
            $markup .= ' />';
        }

        return $markup;
    }

    function parse($text)
    {
        $markup = $this->text($text);

        return $markup;
    }

    function text($text)
    {
        $this->definitionData = [];
        $text                 = str_replace(["\r\n", "\r"], "\n", $text);
        $text                 = trim($text, "\n");
        $lines                = explode("\n", $text);
        $markup               = $this->lines($lines);
        $markup               = trim($markup, "\n");

        return $markup;
    }

    private function blockCode($line, $block = null)
    {
        if (isset($block) && !isset($block['type']) && !isset($block['interrupted'])) {
            return null;
        }
        if ($line['indent'] >= 4) {
            $text  = substr($line['body'], 4);
            $block = [
                'element' => [
                    'name'    => 'pre',
                    'handler' => 'element',
                    'text'    => [
                        'name' => 'code',
                        'text' => $text,
                    ],
                ],
            ];

            return $block;
        }

        return null;
    }

    private function blockCodeContinue($line, $block)
    {
        if ($line['indent'] >= 4) {
            if (isset($block['interrupted'])) {
                $block['element']['text']['text'] .= "\n";
                unset($block['interrupted']);
            }
            $block['element']['text']['text'] .= "\n";
            $text = substr($line['body'], 4);
            $block['element']['text']['text'] .= $text;

            return $block;
        }

        return null;
    }

    private function blockCodeComplete($block)
    {
        $text                             = $block['element']['text']['text'];
        $text                             = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');
        $block['element']['text']['text'] = $text;

        return $block;
    }

    private function blockComment($line)
    {
        if ($this->markupEscaped) {
            return null;
        }
        if (isset($line['text'][3]) && $line['text'][3] === '-' && $line['text'][2] === '-' && $line['text'][1] === '!') {
            $block = [
                'markup' => $line['body'],
            ];
            if (preg_match('/-->$/', $line['text'])) {
                $block['closed'] = true;
            }

            return $block;
        }

        return null;
    }

    private function blockCommentContinue($line, array $block)
    {
        if (isset($block['closed'])) {
            return null;
        }
        $block['markup'] .= "\n" . $line['body'];
        if (preg_match('/-->$/', $line['text'])) {
            $block['closed'] = true;
        }

        return $block;
    }

    private function blockFencedCode($line)
    {
        if (preg_match('/^[' . $line['text'][0] . ']{3,}[ ]*([\w-]+)?[ ]*$/', $line['text'], $matches)) {
            $element = [
                'name' => 'code',
                'text' => '',
            ];
            if (isset($matches[1])) {
                $class                 = 'language-' . $matches[1];
                $element['attributes'] = [
                    'class' => $class,
                ];
            }
            $block = [
                'char'    => $line['text'][0],
                'element' => [
                    'name'    => 'pre',
                    'handler' => 'element',
                    'text'    => $element,
                ],
            ];

            return $block;
        }

        return null;
    }

    private function blockFencedCodeContinue($line, $block)
    {
        if (isset($block['complete'])) {
            return null;
        }
        if (isset($block['interrupted'])) {
            $block['element']['text']['text'] .= "\n";
            unset($block['interrupted']);
        }
        if (preg_match('/^' . $block['char'] . '{3,}[ ]*$/', $line['text'])) {
            $block['element']['text']['text'] = substr($block['element']['text']['text'], 1);
            $block['complete']                = true;

            return $block;
        }
        $block['element']['text']['text'] .= "\n" . $line['body'];;

        return $block;
    }

    private function blockFencedCodeComplete($block)
    {
        $text                             = $block['element']['text']['text'];
        $text                             = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');
        $block['element']['text']['text'] = $text;

        return $block;
    }

    private function blockHeader($line)
    {
        if (isset($line['text'][1])) {
            $level = 1;
            while (isset($line['text'][$level]) && $line['text'][$level] === '#') {
                $level++;
            }
            if ($level > 6) {
                return null;
            }
            $text  = trim($line['text'], '# ');
            $block = [
                'element' => [
                    'name'    => 'h' . min(6, $level),
                    'text'    => $text,
                    'handler' => 'line',
                ],
            ];

            return $block;
        }

        return null;
    }

    private function blockList($line)
    {
        list($name, $pattern) = $line['text'][0] <= '-' ? ['ul', '[*+-]'] : ['ol', '[0-9]+[.]'];
        if (preg_match('/^(' . $pattern . '[ ]+)(.*)/', $line['text'], $matches)) {
            $block                       = [
                'indent'  => $line['indent'],
                'pattern' => $pattern,
                'element' => [
                    'name'    => $name,
                    'handler' => 'elements',
                ],
            ];
            $block['li']                 = [
                'name'    => 'li',
                'handler' => 'li',
                'text'    => [
                    $matches[2],
                ],
            ];
            $block['element']['text'] [] = &$block['li'];

            return $block;
        }

        return null;
    }

    private function blockListContinue($line, array $block)
    {
        if ($block['indent'] === $line['indent'] && preg_match('/^' . $block['pattern'] . '(?:[ ]+(.*)|$)/', $line['text'], $matches)) {
            if (isset($block['interrupted'])) {
                $block['li']['text'] [] = '';
                unset($block['interrupted']);
            }
            unset($block['li']);
            $text                        = isset($matches[1]) ? $matches[1] : '';
            $block['li']                 = [
                'name'    => 'li',
                'handler' => 'li',
                'text'    => [
                    $text,
                ],
            ];
            $block['element']['text'] [] = &$block['li'];

            return $block;
        }
        if ($line['text'][0] === '[' && $this->blockReference($line)) {
            return $block;
        }
        if (!isset($block['interrupted'])) {
            $text                   = preg_replace('/^[ ]{0,4}/', '', $line['body']);
            $block['li']['text'] [] = $text;

            return $block;
        }
        if ($line['indent'] > 0) {
            $block['li']['text'] [] = '';
            $text                   = preg_replace('/^[ ]{0,4}/', '', $line['body']);
            $block['li']['text'] [] = $text;
            unset($block['interrupted']);

            return $block;
        }

        return null;
    }

    private function blockReference($line)
    {
        if (preg_match('/^\[(.+?)\]:[ ]*<?(\S+?)>?(?:[ ]+["\'(](.+)["\')])?[ ]*$/', $line['text'], $matches)) {
            $id   = strtolower($matches[1]);
            $Data = [
                'url'   => $matches[2],
                'title' => null,
            ];
            if (isset($matches[3])) {
                $Data['title'] = $matches[3];
            }
            $this->definitionData['Reference'][$id] = $Data;
            $block                                  = [
                'hidden' => true,
            ];

            return $block;
        }

        return null;
    }

    private function blockQuote($line)
    {
        if (preg_match('/^>[ ]?(.*)/', $line['text'], $matches)) {
            $block = [
                'element' => [
                    'name'    => 'blockquote',
                    'handler' => 'lines',
                    'text'    => (array) $matches[1],
                ],
            ];

            return $block;
        }

        return null;
    }

    private function blockQuoteContinue($line, array $block)
    {
        if ($line['text'][0] === '>' && preg_match('/^>[ ]?(.*)/', $line['text'], $matches)) {
            if (isset($block['interrupted'])) {
                $block['element']['text'] [] = '';
                unset($block['interrupted']);
            }
            $block['element']['text'] [] = $matches[1];

            return $block;
        }
        if (!isset($block['interrupted'])) {
            $block['element']['text'] [] = $line['text'];

            return $block;
        }

        return null;
    }

    private function blockRule($line)
    {
        if (preg_match('/^([' . $line['text'][0] . '])([ ]*\1){2,}[ ]*$/', $line['text'])) {
            $block = [
                'element' => [
                    'name' => 'hr'
                ],
            ];

            return $block;
        }

        return null;
    }

    private function blockSetextHeader($line, array $block = null)
    {
        if (!isset($block) || isset($block['type']) || isset($block['interrupted'])) {
            return null;
        }
        if (chop($line['text'], $line['text'][0]) === '') {
            $block['element']['name'] = $line['text'][0] === '=' ? 'h1' : 'h2';

            return $block;
        }

        return null;
    }

    private function blockMarkup($line)
    {
        if ($this->markupEscaped) {
            return null;
        }
        if (preg_match('/^<(\w*)(?:[ ]*' . $this->regexHtmlAttribute . ')*[ ]*(\/)?>/', $line['text'], $matches)) {
            $element = strtolower($matches[1]);
            if (in_array($element, $this->textLevelElements)) {
                return null;
            }
            $block     = [
                'name'   => $matches[1],
                'depth'  => 0,
                'markup' => $line['text'],
            ];
            $length    = strlen($matches[0]);
            $remainder = substr($line['text'], $length);
            if (trim($remainder) === '') {
                if (isset($matches[2]) || in_array($matches[1], $this->voidElements)) {
                    $block['closed'] = true;
                    $block['void']   = true;
                }
            } else {
                if (isset($matches[2]) || in_array($matches[1], $this->voidElements)) {
                    return null;
                }
                if (preg_match('/<\/' . $matches[1] . '>[ ]*$/i', $remainder)) {
                    $block['closed'] = true;
                }
            }

            return $block;
        }

        return null;
    }

    private function blockMarkupContinue($line, array $block)
    {
        if (isset($block['closed'])) {
            return null;
        }
        if (preg_match('/^<' . $block['name'] . '(?:[ ]*' . $this->regexHtmlAttribute . ')*[ ]*>/i', $line['text'])) {
            # open
        {
            $block['depth']++;
        }
        }
        if (preg_match('/(.*?)<\/' . $block['name'] . '>[ ]*$/i', $line['text'], $matches)) {
            # close
        {
            if ($block['depth'] > 0) {
                $block['depth']--;
        }
            } else {
                $block['closed'] = true;
            }
        }
        if (isset($block['interrupted'])) {
            $block['markup'] .= "\n";
            unset($block['interrupted']);
        }
        $block['markup'] .= "\n" . $line['body'];

        return $block;
    }

    private function blockTable($line, array $block = null)
    {
        if (!isset($block) || isset($block['type']) || isset($block['interrupted'])) {
            return null;
        }
        if (strpos($block['element']['text'], '|') !== false && chop($line['text'], ' -:|') === '') {
            $alignments   = [];
            $divider      = $line['text'];
            $divider      = trim($divider);
            $divider      = trim($divider, '|');
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
                if (substr($dividerCell, -1) === ':') {
                    $alignment = $alignment === 'left' ? 'center' : 'right';
                }
                $alignments [] = $alignment;
            }

            $HeaderElements = [];
            $header         = $block['element']['text'];
            $header         = trim($header);
            $header         = trim($header, '|');
            $headerCells    = explode('|', $header);
            foreach ($headerCells as $index => $headerCell) {
                $headerCell    = trim($headerCell);
                $HeaderElement = [
                    'name'    => 'th',
                    'text'    => $headerCell,
                    'handler' => 'line',
                ];
                if (isset($alignments[$index])) {
                    $alignment                   = $alignments[$index];
                    $HeaderElement['attributes'] = [
                        'style' => 'text-align: ' . $alignment . ';',
                    ];
                }
                $HeaderElements [] = $HeaderElement;
            }

            $block                                  = [
                'alignments' => $alignments,
                'identified' => true,
                'element'    => [
                    'name'    => 'table',
                    'handler' => 'elements',
                ],
            ];
            $block['element']['text'] []            = [
                'name'    => 'thead',
                'handler' => 'elements',
            ];
            $block['element']['text'] []            = [
                'name'    => 'tbody',
                'handler' => 'elements',
                'text'    => [],
            ];
            $block['element']['text'][0]['text'] [] = [
                'name'    => 'tr',
                'handler' => 'elements',
                'text'    => $HeaderElements,
            ];

            return $block;
        }

        return null;
    }

    private function blockTableContinue($line, array $block)
    {
        if (isset($block['interrupted'])) {
            return null;
        }
        if ($line['text'][0] === '|' || strpos($line['text'], '|')) {
            $elements = [];
            $row      = $line['text'];
            $row      = trim($row);
            $row      = trim($row, '|');
            preg_match_all('/(?:(\\\\[|])|[^|`]|`[^`]+`|`)+/', $row, $matches);
            foreach ($matches[0] as $index => $cell) {
                $cell    = trim($cell);
                $element = [
                    'name'    => 'td',
                    'handler' => 'line',
                    'text'    => $cell,
                ];
                if (isset($block['alignments'][$index])) {
                    $element['attributes'] = [
                        'style' => 'text-align: ' . $block['alignments'][$index] . ';',
                    ];
                }
                $elements [] = $element;
            }
            $element                                = [
                'name'    => 'tr',
                'handler' => 'elements',
                'text'    => $elements,
            ];
            $block['element']['text'][1]['text'] [] = $element;

            return $block;
        }

        return null;
    }

    private function inlineCode($excerpt)
    {
        $marker = $excerpt['text'][0];
        if (preg_match('/^(' . $marker . '+)[ ]*(.+?)[ ]*(?<!' . $marker . ')\1(?!' . $marker . ')/s', $excerpt['text'], $matches)) {
            $text = $matches[2];
            $text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');
            $text = preg_replace("/[ ]*\n/", ' ', $text);

            return [
                'extent'  => strlen($matches[0]),
                'element' => [
                    'name' => 'code',
                    'text' => $text,
                ],
            ];
        }

        return null;
    }

    private function inlineEmailTag($excerpt)
    {
        if (strpos($excerpt['text'], '>') !== false && preg_match('/^<((mailto:)?\S+?@\S+?)>/i', $excerpt['text'], $matches)) {
            $url = $matches[1];
            if (!isset($matches[2])) {
                $url = 'mailto:' . $url;
            }

            return [
                'extent'  => strlen($matches[0]),
                'element' => [
                    'name'       => 'a',
                    'text'       => $matches[1],
                    'attributes' => [
                        'href' => $url,
                    ],
                ],
            ];
        }

        return null;
    }

    private function inlineEmphasis($excerpt)
    {
        if (!isset($excerpt['text'][1])) {
            return null;
        }
        $marker = $excerpt['text'][0];
        if ($excerpt['text'][1] === $marker && preg_match($this->strongRegex[$marker], $excerpt['text'], $matches)) {
            $emphasis = 'strong';
        } elseif (preg_match($this->emRegex[$marker], $excerpt['text'], $matches)) {
            $emphasis = 'em';
        } else {
            return null;
        }

        return [
            'extent'  => strlen($matches[0]),
            'element' => [
                'name'    => $emphasis,
                'handler' => 'line',
                'text'    => $matches[1],
            ],
        ];
    }

    private function inlineEscapeSequence($excerpt)
    {
        if (isset($excerpt['text'][1]) && in_array($excerpt['text'][1], $this->specialCharacters)) {
            return [
                'markup' => $excerpt['text'][1],
                'extent' => 2,
            ];
        }

        return null;
    }

    private function inlineImage($excerpt)
    {
        if (!isset($excerpt['text'][1]) || $excerpt['text'][1] !== '[') {
            return null;
        }
        $excerpt['text'] = substr($excerpt['text'], 1);
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
                    'alt' => $link['element']['text'],
                ],
            ],
        ];
        $inline['element']['attributes'] += $link['element']['attributes'];
        unset($inline['element']['attributes']['href']);

        return $inline;
    }

    private function inlineLink($excerpt)
    {
        $element   = [
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
        if (preg_match('/\[((?:[^][]|(?R))*)\]/', $remainder, $matches)) {
            $element['text'] = $matches[1];
            $extent += strlen($matches[0]);
            $remainder = substr($remainder, $extent);
        } else {
            return null;
        }
        if (preg_match('/^[(]((?:[^ ()]|[(][^ )]+[)])+)(?:[ ]+("[^"]*"|\'[^\']*\'))?[)]/', $remainder, $matches)) {
            $element['attributes']['href'] = $matches[1];
            if (isset($matches[2])) {
                $element['attributes']['title'] = substr($matches[2], 1, -1);
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
            if (!isset($this->definitionData['Reference'][$definition])) {
                return null;
            }
            $Definition                     = $this->definitionData['Reference'][$definition];
            $element['attributes']['href']  = $Definition['url'];
            $element['attributes']['title'] = $Definition['title'];
        }
        $element['attributes']['href'] = str_replace(['&', '<'], ['&amp;', '&lt;'], $element['attributes']['href']);

        return [
            'extent'  => $extent,
            'element' => $element,
        ];
    }

    private function inlineMarkup($excerpt)
    {
        if ($this->markupEscaped || strpos($excerpt['text'], '>') === false) {
            return null;
        }
        if ($excerpt['text'][1] === '/' && preg_match('/^<\/\w*[ ]*>/s', $excerpt['text'], $matches)) {
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
        if ($excerpt['text'][1] !== ' ' && preg_match('/^<\w*(?:[ ]*' . $this->regexHtmlAttribute . ')*[ ]*\/?>/s', $excerpt['text'], $matches)) {
            return [
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            ];
        }

        return null;
    }

    private function inlineSpecialCharacter($excerpt)
    {
        if ($excerpt['text'][0] === '&' && !preg_match('/^&#?\w+;/', $excerpt['text'])) {
            return [
                'markup' => '&amp;',
                'extent' => 1,
            ];
        }
        $SpecialCharacter = ['>' => 'gt', '<' => 'lt', '"' => 'quot'];
        if (isset($SpecialCharacter[$excerpt['text'][0]])) {
            return [
                'markup' => '&' . $SpecialCharacter[$excerpt['text'][0]] . ';',
                'extent' => 1,
            ];
        }

        return null;
    }

    private function inlineStrikethrough($excerpt)
    {
        if (!isset($excerpt['text'][1])) {
            return null;
        }
        if ($excerpt['text'][1] === '~' && preg_match('/^~~(?=\S)(.+?)(?<=\S)~~/', $excerpt['text'], $matches)) {
            return [
                'extent'  => strlen($matches[0]),
                'element' => [
                    'name'    => 'del',
                    'text'    => $matches[1],
                    'handler' => 'line',
                ],
            ];
        }

        return null;
    }

    private function inlineUrl($excerpt)
    {
        if ($this->urlsLinked !== true || !isset($excerpt['text'][2]) || $excerpt['text'][2] !== '/') {
            return null;
        }
        if (preg_match('/\bhttps?:[\/]{2}[^\s<]+\b\/*/ui', $excerpt['context'], $matches, PREG_OFFSET_CAPTURE)) {
            $inline = [
                'extent'   => strlen($matches[0][0]),
                'position' => $matches[0][1],
                'element'  => [
                    'name'       => 'a',
                    'text'       => $matches[0][0],
                    'attributes' => [
                        'href' => $matches[0][0],
                    ],
                ],
            ];

            return $inline;
        }

        return null;
    }

    private function inlineUrlTag($excerpt)
    {
        if (strpos($excerpt['text'], '>') !== false && preg_match('/^<(\w+:\/{2}[^ >]+)>/i', $excerpt['text'], $matches)) {
            $url = str_replace(['&', '<'], ['&amp;', '&lt;'], $matches[1]);

            return [
                'extent'  => strlen($matches[0]),
                'element' => [
                    'name'       => 'a',
                    'text'       => $url,
                    'attributes' => [
                        'href' => $url,
                    ],
                ],
            ];
        }

        return null;
    }

    private function elements(array $elements)
    {
        $markup = '';
        foreach ($elements as $element) {
            $markup .= "\n" . $this->element($element);
        }
        $markup .= "\n";

        return $markup;
    }

    private function li($lines)
    {
        $markup        = $this->lines($lines);
        $trimmedMarkup = trim($markup);
        if (!in_array('', $lines) && substr($trimmedMarkup, 0, 3) === '<p>') {
            $markup   = $trimmedMarkup;
            $markup   = substr($markup, 3);
            $position = strpos($markup, "</p>");
            $markup   = substr_replace($markup, '', $position, 4);
        }

        return $markup;
    }

    private function lines(array $lines)
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
                $line  = $parts[0];
                unset($parts[0]);
                foreach ($parts as $part) {
                    $shortage = 4 - mb_strlen($line, 'utf-8') % 4;
                    $line .= str_repeat(' ', $shortage);
                    $line .= $part;
                }
            }
            $indent = 0;
            while (isset($line[$indent]) && $line[$indent] === ' ') {
                $indent++;
            }
            $text = $indent > 0 ? substr($line, $indent) : $line;

            $line = ['body' => $line, 'indent' => $indent, 'text' => $text];

            if (isset($currentBlock['continuable'])) {
                $block = $this->{'block' . $currentBlock['type'] . 'Continue'}($line, $currentBlock);
                if (isset($block)) {
                    $currentBlock = $block;
                    continue;
                } else {
                    if (method_exists($this, 'block' . $currentBlock['type'] . 'Complete')) {
                        $currentBlock = $this->{'block' . $currentBlock['type'] . 'Complete'}($currentBlock);
                    }
                }
            }

            $marker = $text[0];

            $blockTypes = $this->unmarkedBlockTypes;
            if (isset($this->blockTypes[$marker])) {
                foreach ($this->blockTypes[$marker] as $blockType) {
                    $blockTypes [] = $blockType;
                }
            }

            foreach ($blockTypes as $blockType) {
                $block = $this->{'block' . $blockType}($line, $currentBlock);
                if (isset($block)) {
                    $block['type'] = $blockType;
                    if (!isset($block['identified'])) {
                        $blocks[]            = $currentBlock;
                        $block['identified'] = true;
                    }
                    if (method_exists($this, 'block' . $blockType . 'Continue')) {
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
                $currentBlock               = $this->paragraph($line);
                $currentBlock['identified'] = true;
            }
        }

        if (isset($currentBlock['continuable']) && method_exists($this, 'block' . $currentBlock['type'] . 'Complete')) {
            $currentBlock = $this->{'block' . $currentBlock['type'] . 'Complete'}($currentBlock);
        }

        $blocks[] = $currentBlock;
        unset($blocks[0]);

        $markup = '';
        foreach ($blocks as $block) {
            if (isset($block['hidden'])) {
                continue;
            }
            $markup .= "\n";
            $markup .= isset($block['markup']) ? $block['markup'] : $this->element($block['element']);
        }
        $markup .= "\n";

        return $markup;
    }

    private function paragraph($line)
    {
        $block = [
            'element' => [
                'name'    => 'p',
                'text'    => $line['text'],
                'handler' => 'line',
            ],
        ];

        return $block;
    }
}

