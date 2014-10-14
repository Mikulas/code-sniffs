<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.Debug.ClassDebuggerCall';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
Debugger::barDump('foo');
CONTENT
);
Assert::same(array('Found'), $cs->rules);

$cs = runPhpcs($sniff, <<<CONTENT
<?php
OkClass::dump('foo');
CONTENT
);
Assert::same(0, $cs->errors);
