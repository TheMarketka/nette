<?php

/**
 * Test: Nette\DI\ContainerBuilder and anonymous services.
 *
 * @author     David Grudl
 * @package    Nette\DI
 */

use Nette\DI,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class Service
{
}


$builder = new DI\ContainerBuilder;
$builder->addDefinition('\Service')
	->setClass('self');

$builder->addDefinition('\stdClass')
	->setFactory('self')
	->addSetup('$value', '@\Service');


$code = (string) $builder->generateClass();
file_put_contents(TEMP_DIR . '/code.php', "<?php\n$code");
require TEMP_DIR . '/code.php';

$container = new Container;


Assert::type( 'Service', $container->getByType('Service') );
Assert::type( 'stdClass', $container->getByType('stdClass') );
