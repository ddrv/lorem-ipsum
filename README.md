Lorem Ipsum
===========

Install
-------

```
composer require lorem/ipsum
```

Usage
-----

```php
<?php
include ('vendor/autoload.php');

$loremIpsum = new \Lorem\Ipsum\Generator();
$loremIpsum->addParagraph(2)
    ->addParagraph(8)
    ->wrapAll('article', array('class'=>'lorem-ipsum'))
    ->wrapParagraph(1, 'h1')
    ->wrapParagraph(2, 'p', array('class'=>'lorem-ipsum'))
    ->wrapWord(2, 6, 'span')
    ->wrapWords(2, 1, 3, 'a', array('href'=>'https://packagist.org/packages/lorem/ipsum'));
echo $loremIpsum;
```

Result
------

```html
<article class="lorem-ipsum">
<h1>Lorem ipsum</h1>
<p class="lorem-ipsum"><a href="https://packagist.org/packages/lorem/ipsum">Lorem ipsum dolor</a> sit amet, <span>consectetur</span> adipiscing elit</p>
</article>
```
