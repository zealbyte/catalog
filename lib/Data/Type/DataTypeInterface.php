<?php
namespace ZealByte\Catalog\Data\Type
{
	interface DataTypeInterface
	{
		public function convert ($data, array $options);

		public function reverseConvert ($data, array $options);
	}
}
