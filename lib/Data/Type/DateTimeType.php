<?php
namespace ZealByte\Catalog\Data\Type
{
	use DateTime;

	class DateTimeType implements DataTypeInterface
	{
		public function convert ($data, array $options)
		{
			return $data;
		}

		public function reverseConvert ($data, array $options)
		{
			if (!$data)
				$data = 'now';

			return new DateTime('now');
		}
	}
}
