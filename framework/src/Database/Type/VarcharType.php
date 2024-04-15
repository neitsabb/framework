<?php

namespace Neitsab\Framework\Database\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class VarcharType extends Type
{
	// Définissez le nom du type
	public function getName()
	{
		return 'varchar';
	}

	public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
	{
		return 'VARCHAR(255)';
	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
	{
		return parent::convertToDatabaseValue($value, $platform);
	}
}
