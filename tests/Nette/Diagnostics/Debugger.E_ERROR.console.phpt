<?php

/**
 * Test: Nette\Diagnostics\Debugger E_ERROR in console.
 *
 * @author     David Grudl
 * @package    Nette\Diagnostics
 * @exitCode   254
 * @httpCode   500
 * @outputMatch OK!
 */

use Nette\Diagnostics\Debugger,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Debugger::$consoleMode = TRUE;
Debugger::$productionMode = FALSE;

Debugger::enable();

Debugger::$onFatalError[] = function() {
	Assert::match("
Fatal error: Call to undefined function missing_function() in %a%
exception 'Nette\\FatalErrorException' with message 'Call to undefined function missing_function()' in %a%
Stack trace:
#0 [internal function]: %a%Debugger::_shutdownHandler()
#1 {main}
(stored in %a%)
", ob_get_clean());
	echo 'OK!';
};
ob_start();


function first($arg1, $arg2)
{
	second(TRUE, FALSE);
}


function second($arg1, $arg2)
{
	third(array(1, 2, 3));
}


function third($arg1)
{
	missing_function();
}


first(10, 'any string');
