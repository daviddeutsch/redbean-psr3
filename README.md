RedBean PSR-3
=============

A PSR-3 standard compliant logger for RedBeanPHP.

### Usage

```php
// Include or autoload the files in this library

R::ext( 'logger', array('RedBean_Psr3', 'instance') );

R::logger()->warning('Nuclear Annihilation');

R::logger()->notice('Note to self: Think over logging level of nuclear things.');

// Also instance safe (daviddeutsch/redbean-instance)

R::ext( 'logger', array('RedBean_Psr3', 'instance') );

$db = R::instance();

$db->log = $db->logger();

$db->log->warning('So this still works.');

// Handles context by making it into JSON

$massage = array(
   'duration'     => '5 minutes',
   'satisfaction' => 'questionable'
)

R::logger()->notice('Here is your massage', $massage);

/*
 * $object->id      = 123;
 * $object->level   = 'notice';
 * $object->message = 'Here is your message';
 * $object->context = '{"duration":"5 minutes","satisfaction":"questionable"}';
 */

// Unless you want to "expand" certain context members into their own fields

R::logger()->expandContext(
   array('domain', 'subdomain', 'locale', 'url', 'quality')
)

// Yup, array or object doesn't matter
$joke = (object) array(
   'domain'    => 'humor',
   'subdomain' => 'language',
   'locale'    => 'en-GB',
   'url'       => 'github.com/daviddeutsch/redbean-psr3/README.md',
   'quality'   => 'low',
   'duration'  => 'short glance',
   'success'   => false
)

R::logger()->notice('A joke on words', $joke);

/*
 * $object->id        = 124;
 * $object->level     = 'notice';
 * $object->message   = 'A joke on words';
 * $object->domain    = 'humor';
 * $object->subdomain = 'language';
 * $object->locale    = 'en-GB';
 * $object->url       = 'github.com/daviddeutsch/redbean-psr3/README.md';
 * $object->quality   = 'low';
 * $object->context   = '{"duration":"short glance","success":false}';
 */


```
