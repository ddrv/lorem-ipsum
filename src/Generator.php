<?php

namespace Lorem\Ipsum;

use InvalidArgumentException;

/**
 * Class Generator
 *
 * @property array $text
 * @property array $words
 * @property array $wrap
 * @property string $default
 */
class Generator
{
    /**
     * @var array
     */
    protected $text = array();

    /**
     * @var array
     */
    protected $wrap = array();

    /**
     * @var string
     */
    protected $default = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
        incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
        ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit
        in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat
        cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';

    /**
     * @var array
     */
    protected $words = array();

    /**
     * Generator constructor.
     */
    public function __construct()
    {
        $this->default = preg_replace('/\n/ui','',$this->default);
        $this->default = preg_replace('/[\t\s]+/ui',' ',$this->default);
        $this->words = explode(' ',$this->default);
        return $this;
    }

    /**
     * @param int $wordsCount
     *
     * @return $this
     */
    public function addParagraph($wordsCount)
    {
        $wordsCount = (int)$wordsCount;
        $source = $this->words;
        while ($wordsCount > count($source)) {
            $source = array_merge($source, $this->words);
        }
        $words = empty($wordsCount)?$source:array_slice($source,0, $wordsCount);
        $this->text[] = $words;
        return $this;
    }

    /**
     * @param string $tag
     * @param array $attributes
     * @return $this
     */
    public function wrapAll($tag, $attributes=array())
    {
        $this->wrap['all'] = array(
            'tag' => '',
            'attributes' => array(),
        );
        if (self::checkTag($tag)) {
            $this->wrap['all']['tag'] = $tag;
            foreach ($attributes as $attribute=>$value) {
                if(self::checkAttribute($attribute)) {
                    $this->wrap['all']['attributes'][$attribute] = addslashes($value);
                }
            }

        }
        return $this;
    }

    /**
     * @param integer $paragraph
     * @param string $tag
     * @param array $attributes
     * @return $this
     * @throws InvalidArgumentException
     */
    public function wrapParagraph($paragraph, $tag, $attributes=array())
    {
        if (!is_integer($paragraph)) {
            throw new InvalidArgumentException('parameter paragraph must be integer');
        }

        $this->wrap[$paragraph]['paragraph'] = array(
            'tag' => '',
            'attributes' => array(),
        );
        if (self::checkTag($tag)) {
            $this->wrap[$paragraph]['paragraph']['tag'] = $tag;
            foreach ($attributes as $attribute=>$value) {
                if(self::checkAttribute($attribute)) {
                    $this->wrap[$paragraph]['paragraph']['attributes'][$attribute] = addslashes($value);
                }
            }

        }
        return $this;
    }

    /**
     * @param integer $paragraph
     * @param integer $word
     * @param string $tag
     * @param array $attributes
     * @return $this
     * @throws InvalidArgumentException
     */
    public function wrapWord($paragraph, $word, $tag, $attributes=array())
    {
        if (!is_integer($paragraph)) {
            throw new InvalidArgumentException('parameter paragraph must be integer');
        }
        if (!is_integer($word)) {
            throw new InvalidArgumentException('parameter word must be integer');
        }

        $this->wrap[$paragraph][$word] = array(
            'len' => 0,
            'tag' => '',
            'attributes' => array(),
        );
        if (self::checkTag($tag)) {
            $this->wrap[$paragraph][$word]['tag'] = $tag;
            foreach ($attributes as $attribute=>$value) {
                if(self::checkAttribute($attribute)) {
                    $this->wrap[$paragraph][$word]['attributes'][$attribute] = addslashes($value);
                }
            }

        }
        return $this;
    }

    /**
     * @param integer $paragraph
     * @param integer $start
     * @param integer $len
     * @param string $tag
     * @param array $attributes
     * @return $this
     * @throws InvalidArgumentException
     */
    public function wrapWords($paragraph, $start, $len, $tag, $attributes=array())
    {
        if (!is_integer($paragraph)) {
            throw new InvalidArgumentException('parameter paragraph must be integer');
        }
        if (!is_integer($start)) {
            throw new InvalidArgumentException('parameter start must be integer');
        }
        if (!is_integer($len)) {
            throw new InvalidArgumentException('parameter len must be integer');
        }
        if ($len<1) {
            throw new InvalidArgumentException('parameter len must be positive integer');
        }

        $this->wrap[$paragraph][$start] = array(
            'len' => $len,
            'tag' => '',
            'attributes' => array(),
        );
        if (self::checkTag($tag)) {
            $this->wrap[$paragraph][$start]['len'] = $len;
            $this->wrap[$paragraph][$start]['tag'] = $tag;
            foreach ($attributes as $attribute=>$value) {
                if(self::checkAttribute($attribute)) {
                    $this->wrap[$paragraph][$start]['attributes'][$attribute] = addslashes($value);
                }
            }

        }
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $string = '';
        if (!empty($this->wrap['all']['tag'])) {
            $string .= '<'.$this->wrap['all']['tag'];
            foreach ($this->wrap['all']['attributes'] as $attribute=>$value) {
                $string .= ' '.$attribute.'="'.$value.'"';
            }
            $string .= '>'.PHP_EOL;
        }
        $paragraphIterator = 0;
        foreach ($this->text as $i=>$words) {
            $paragraphIterator++;

            if (!empty($this->wrap[$paragraphIterator]['paragraph']['tag'])) {
                $string .= '<'.$this->wrap[$paragraphIterator]['paragraph']['tag'];
                foreach ($this->wrap[$paragraphIterator]['paragraph']['attributes'] as $attribute=>$value) {
                    $string .= ' '.$attribute.'="'.$value.'"';
                }
                $string .= '>';
            }
            if (!empty($this->wrap[$paragraphIterator])) {
                foreach ($this->wrap[$paragraphIterator] as $key=>$wrap) {
                    if (isset($words[$key-1])) {
                        if (!empty($wrap['tag'])) {
                            $openTag = '<' . $wrap['tag'];
                            foreach ($wrap['attributes'] as $attribute => $value) {
                                $openTag .= ' ' . $attribute . '="' . $value . '"';
                            }
                            $openTag .= '>';
                            $words[$key - 1] = $openTag.$words[$key - 1];

                            $closedTag = '</'.$wrap['tag'].'>';
                            $last = isset($words[$key - 1 + $wrap['len']])?$key - 1 + $wrap['len']:(count($words)-1);
                            $words[$last] .= $closedTag;
                        }
                    }
                }
            }

            $paragraphText = implode(' ',$words);
            if (in_array(mb_substr($paragraphText,-1,1),array(',','.'))) {
                $paragraphText = mb_substr($paragraphText,0,-1);
            }
            $string .= $paragraphText;
            if (!empty($this->wrap[$paragraphIterator]['paragraph']['tag'])) {
                $string .= '</'.$this->wrap[$paragraphIterator]['paragraph']['tag'].'>';
            }

            $string .= PHP_EOL;

        }
        if (!empty($this->wrap['all']['tag'])) {
            $string .= '</'.$this->wrap['all']['tag'].'>';
        }
        return $string;
    }

    /**
     * @param string $tag
     * @return boolean
     */
    public static function checkTag($tag)
    {
        return preg_match('/^[\w\d_]+$/ui',$tag)?true:false;
    }

    /**
     * @param string $attribute
     * @return bool
     */
    public static function checkAttribute($attribute)
    {
        return preg_match('/^[\w\d_\-]+$/ui',$attribute)?true:false;
    }
}
