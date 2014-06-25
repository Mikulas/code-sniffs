<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.PHP.KeywordCase';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
'x' and 'y' or 'z' xor 'y'
CONTENT
);

Assert::same(['Found', 'Found', 'Found'], $cs->rules);


$cs = runPhpcs($sniff, <<<CONTENT
<?php
FOREACH ([] as \$a]) {}
While (1)
CONTENT
);

Assert::same(['Found', 'Found'], $cs->rules);
