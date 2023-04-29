<?php
/**
 * PHPUnit bootstrap file for WordPress plugin
 */

declare(strict_types=1);

$tmpDir = getenv('TMPDIR');
$tmpDir = $tmpDir ? $tmpDir : '/tmp';

$wpTestsDir = getenv('WP_TESTS_LIB_DIR');
$wpTestsDir = $wpTestsDir ? $wpTestsDir : $tmpDir . '/wordpress-tests-lib';

$wpCoreDir = getenv('WP_TESTS_CORE_DIR');
$wpCoreDir = $wpCoreDir ? $wpCoreDir : $tmpDir . '/wordpress';

$composerDir = dirname(__DIR__, 2) . '/vendor';

if (! file_exists($wpTestsDir . '/includes/functions.php')) {
	throw new Exception(sprintf('Could not find %s/includes/functions.php, have you run bin/install-wp-tests.sh ?', $wpTestsDir));
}

$patchwork = $composerDir . '/antecedent/patchwork/Patchwork.php';

if (file_exists($patchwork)) {
	require_once $patchwork;
}

$autoloader = $composerDir . '/autoload.php';

if (file_exists($autoloader)) {
	require_once $autoloader;
}

// Start up the WP testing environment.
require $wpTestsDir . '/includes/bootstrap.php';
