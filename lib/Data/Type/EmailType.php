<?php
namespace ZealByte\Catalog\Data\Type
{
	class EmailType implements DataTypeInterface
	{
		public function convert ($data, array $options)
		{
			return $data;
		}

		public function reverseConvert ($data, array $options)
		{
			return $data;
		}
	}
}
