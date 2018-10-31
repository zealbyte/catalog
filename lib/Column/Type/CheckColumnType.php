<?php
namespace ZealByte\Catalog\Column\Type
{
	use ZealByte\Catalog\Column\ColumnDefinitionInterface;

	class CheckColumnType implements ColumnTypeInterface
	{
		public function format (array $field_values, array $options)
		{
			$yes = true;

			foreach ($field_values as $field => $value)
				if (!($value))
					$yes = false;

			return ($yes) ? '<span uk-icon="icon: check"></span>' : '';
		}

	}
}
