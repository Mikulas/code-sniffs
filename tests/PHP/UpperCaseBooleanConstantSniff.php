<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.PHP.UpperCaseBooleanConstant';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
True || false;
CONTENT
);

Assert::same(['Found', 'Found'], $cs->rules);
