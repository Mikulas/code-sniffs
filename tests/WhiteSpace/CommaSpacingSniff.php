<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.WhiteSpace.CommaSpacing';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
list(\$a ,\$b) = [];
CONTENT
);

Assert::same(['NoSpaceAfter', 'SpaceBefore'], $cs->rules);


$cs = runPhpcs($sniff, <<<CONTENT
<?php
list(\$a,  \$b) = [];
CONTENT
);

Assert::same(['NotSingleSpaceAfter'], $cs->rules);
