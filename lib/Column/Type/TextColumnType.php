<?php
namespace ZealByte\Catalog\Column\Type
{
	use ZealByte\Catalog\Column\ColumnDefinitionInterface;
	use ZealByte\Catalog\Column\ColumnDataCallback;

	class TextColumnType implements ColumnTypeInterface
	{
		public function format (array $field_values, array $options)
		{
			return implode(' ', $field_values);
		}

	}
}
