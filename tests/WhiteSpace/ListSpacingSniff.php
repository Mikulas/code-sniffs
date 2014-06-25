<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.WhiteSpace.ListSpacing';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
list (\$a) = [];
CONTENT
);

Assert::same(['SpaceAfter'], $cs->rules);


$cs = runPhpcs($sniff, <<<CONTENT
<?php
list(\$a) = [];
CONTENT
);

Assert::same(0, $cs->errors);
