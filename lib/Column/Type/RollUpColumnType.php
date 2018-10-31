<?php
namespace ZealByte\Catalog\Column\Type
{
	use ZealByte\Catalog\Column\ColumnDefinitionInterface;

	class RollUpColumnType implements ColumnTypeInterface
	{
		public function format (array $field_values, array $options)
		{
			$rolls = [];

			foreach ($field_values as $field => $value) {
				$rolls[] = sprintf('%s: %d', $field, count($value));
			}

			return implode(', ', $rolls);
		}

	}
}
