<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.Debug.DebugFunctionCall';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
dump('foo');
CONTENT
);
Assert::same(array('Found'), $cs->rules);

$cs = runPhpcs($sniff, <<<CONTENT
<?php
notDump('foo');
CONTENT
);
Assert::same(0, $cs->errors);
