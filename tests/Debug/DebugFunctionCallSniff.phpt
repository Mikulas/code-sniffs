<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.Debug.DebugFunctionCall.Found';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
dump('foo');
CONTENT
);
Assert::same(['Found'], $cs->rules);

$cs = runPhpcs($sniff, <<<CONTENT
<?php
notDump('foo');
CONTENT
);
Assert::same(0, $cs->errors);
