<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.Formatting.UseInAlphabeticalOrder';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
use A;
use B;
use X; # line 4
use D;
CONTENT
);
Assert::same(array('UseInAlphabeticalOrder'), $cs->rules);
Assert::same(4, $cs->messages[0]->line);

$cs = runPhpcs($sniff, <<<CONTENT
<?php
use A;
use B;
use C;
CONTENT
);
Assert::same(0, $cs->errors);
