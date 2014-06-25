<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/nette/tester/Tester/bootstrap.php';


/**
 * @param string $sniff
 * @param string $content
 * @return \stdClass
 */
function runPhpcs($sniff, $content)
{
	$file = tempnam('/tmp', 'code_sniff_');
	file_put_contents($file, $content);

	$root = realpath(__DIR__ . '/../');
	$phpcs = escapeshellarg("$root/vendor/bin/phpcs");
	$standard = escapeshellarg("$root/cs");
	$sniff = escapeshellarg($sniff);

	$output = [];
	exec("$phpcs --standard=$standard --report=json $file --sniffs=$sniff", $output);
	$raw = implode($output);

	$data = json_decode($raw);
	if (!$data)
	{
		echo "$data\n";
		exit(1);
	}

	$messages = [];
	$rules = [];
	foreach ($data->files as $file)
	{
		foreach ($file->messages as $message)
		{
			$message->rule = preg_replace('~^.*\.~', '', $message->source);
			$rules[] = $message->rule;
			$messages[] = $message;
		}
	}

	return (object) [
		'warnings' => $data->totals->warnings,
		'errors' => $data->totals->errors,
		'rules' => $rules,
		'messages' => $messages,
	];
}
