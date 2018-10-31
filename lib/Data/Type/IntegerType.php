<?php
namespace ZealByte\Catalog\Data\Type
{
	class IntegerType implements DataTypeInterface
	{
		public function convert ($data, array $options)
		{
			return (int) $data;
		}

		public function reverseConvert ($data, array $options)
		{
			return (int) $data;
		}
	}
}
