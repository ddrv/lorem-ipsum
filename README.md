Install
=======

```
composer require lorem/ipsum
```

Usage
=====

```php
<?php
include ('vendor/autoload.php');

$loremIpsum = new \Lorem\Ipsum\Generator();
$loremIpsum->addParagraph(2)
    ->addParagraph(8)
    ->wrapAll('article', array('class'=>'lorem-ipsum'))
    ->wrapParagraph(1, 'h1', array())
    ->wrapParagraph(2, 'p', array('class'=>'lorem-ipsum'))
    ->wrapWord(2, 6, 'span');
echo $loremIpsum;
```

Result:

```html
<article class="lorem-ipsum">
<h1>Lorem ipsum</h1>
<p class="lorem-ipsum">Lorem ipsum dolor sit amet, <span>consectetur<span> adipiscing elit</p>
</article>
```
