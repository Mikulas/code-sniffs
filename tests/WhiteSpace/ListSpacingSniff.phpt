<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.WhiteSpace.ListSpacing';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
list (\$a) = array();
CONTENT
);

Assert::same(array('SpaceAfter'), $cs->rules);


$cs = runPhpcs($sniff, <<<CONTENT
<?php
list(\$a) = array();
CONTENT
);

Assert::same(0, $cs->errors);
