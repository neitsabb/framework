<?php

namespace Modules\Formatter\Contracts;

interface FormatterInterface
{
	public function format(array|string $value): string;
}
