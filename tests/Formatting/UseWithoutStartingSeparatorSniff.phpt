<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.Formatting.UseWithoutStartingSeparator';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
use \A;
CONTENT
);
Assert::same(array('NotAllowed'), $cs->rules);

$cs = runPhpcs($sniff, <<<CONTENT
<?php
use A;
CONTENT
);
Assert::same(0, $cs->errors);
