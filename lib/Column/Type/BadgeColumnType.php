<?php
namespace ZealByte\Catalog\Column\Type
{
	use ZealByte\Catalog\Column\ColumnDefinitionInterface;

	class BadgeColumnType implements ColumnTypeInterface
	{
		public function format (array $field_values, array $options)
		{
			return [
				'value' => array_shift($field_values),
				'class' => array_shift($field_values),
			];
		}

	}
}
