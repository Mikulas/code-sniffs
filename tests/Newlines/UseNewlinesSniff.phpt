<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.Newlines.UseNewlines';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
use A;

use B;
CONTENT
);

Assert::same(array('ExtraneousNewline'), $cs->rules);
