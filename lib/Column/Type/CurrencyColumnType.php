<?php
namespace ZealByte\Catalog\Column\Type
{
	use ZealByte\Catalog\Column\ColumnDefinitionInterface;

	class CurrencyColumnType implements ColumnTypeInterface
	{
		private $format = '%i';

		public function __construct (?string $format = null)
		{
			setlocale(LC_MONETARY, 'en_US');

			if ($format)
				$this->format = $format;
		}

		public function format (array $field_values, array $options)
		{
			reset($field_values);

			$fieldName = key($field_values);
			$amount = $field_values[$fieldName];

			return money_format($this->format, $amount);
		}

	}
}
