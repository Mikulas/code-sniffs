<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.Newlines.NamespaceNewlines';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
namespace A;
class Foo {}
CONTENT
);

Assert::same(array('NewlineAbove', 'NewlineBelowWithoutUsings'), $cs->rules);


$cs = runPhpcs($sniff, <<<CONTENT
<?php

namespace A;


class Foo {}

CONTENT
);

Assert::same(0, $cs->errors);


$cs = runPhpcs($sniff, <<<CONTENT
<?php

namespace A;

use B;
CONTENT
);

Assert::same(0, $cs->errors);
