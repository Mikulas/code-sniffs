<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.PHP.UpperCaseNullConstant';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
Null || null;
CONTENT
);

Assert::same(array('Found', 'Found'), $cs->rules);
