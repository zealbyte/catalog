<?php
namespace ZealByte\Catalog\Column\Type
{
	use ZealByte\Catalog\Column\ColumnDefinitionInterface;

	class TrueFalseColumnType implements ColumnTypeInterface
	{
		public function format (array $field_values, array $options)
		{
			return $field_values;
		}

	}
}
