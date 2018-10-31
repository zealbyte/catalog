<?php
namespace ZealByte\Catalog\Data\Type
{
	use Ramsey\Uuid\UuidInterface;
	use ZealByte\Util\UUID;

	class UUIDType implements DataTypeInterface
	{
		public function convert ($data, array $options)
		{
			if (\is_resource($data))
				$bytes = stream_get_contents($data);
			else
				$bytes = $data;

			if (empty($bytes))
				return null;

			try {
				return UUID::binToString($bytes);
			} catch (InvalidArgumentException $e) {
				throw $e;
			}
		}

		public function reverseConvert ($data, array $options)
		{
			if (empty($data))
				return null;

			try {
				return UUID::stringToBin($data);
			} catch (InvalidArgumentException $e) {
				throw $e;
			}
		}

	}
}
