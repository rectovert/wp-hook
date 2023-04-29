<?php

declare(strict_types=1);

namespace Rectovert\WP\Hook\Contract;

use Rectovert\WP\Hook\Hook;

interface WithHook
{
	/**
	 * Add WordPress hooks to run.
	 */
	public function hook(Hook $hook): void;
}
