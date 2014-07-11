<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.Formatting.UseInAlphabeticalOrder';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
use C;
use A;
use B;
CONTENT
);
Assert::same(['UseInAlphabeticalOrder'], $cs->rules);
Assert::same(3, $cs->messages[0]->line);

$cs = runPhpcs($sniff, <<<CONTENT
<?php
use A;
use B;
use C;
CONTENT
);
Assert::same(0, $cs->errors);
