<?php
namespace ZealByte\Catalog\Column\Type
{
	use ZealByte\Catalog\Column\ColumnDefinitionInterface;

	interface ColumnTypeInterface
	{
		public function format (array $field_values, array $options);
	}
}
