<?php
namespace ZealByte\Catalog\Column\Type
{
	use DateTimeInterface;
	use ZealByte\Catalog\Column\ColumnDefinitionInterface;

	class DateTimeColumnType implements ColumnTypeInterface
	{
		public function format (array $field_values, array $options)
		{
			reset($field_values);

			$fieldName = key($field_values);
			$dateTime = $field_values[$fieldName];

			if (empty($dateTime))
				return null;

			if (!($dateTime instanceof DateTimeInterface))
				throw new \Exception("DateTimeColumnType expects field $fieldName to be a DateTimeInterface got {gettype($dateTime)}.");

			return $dateTime->format('m/d/y');
		}

	}
}
