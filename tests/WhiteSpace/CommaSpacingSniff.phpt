<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.WhiteSpace.CommaSpacing';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
list(\$a ,\$b) = array();
CONTENT
);

Assert::same(array('NoSpaceAfter', 'SpaceBefore'), $cs->rules);


$cs = runPhpcs($sniff, <<<CONTENT
<?php
list(\$a,  \$b) = array();
CONTENT
);

Assert::same(array('NotSingleSpaceAfter'), $cs->rules);
