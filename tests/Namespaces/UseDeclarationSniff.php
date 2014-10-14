<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.Namespaces.UseDeclaration';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
use A,
	B;


//
CONTENT
);

Assert::same(array('MultipleDeclarations'), $cs->rules);


$cs = runPhpcs($sniff, <<<CONTENT
<?php

use Bar;
class Foo {}
CONTENT
);

Assert::same(array('SpaceAfterLastUse'), $cs->rules);
