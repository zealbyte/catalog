<?php
namespace ZealByte\Catalog\Util
{
	use IteratorIterator;
	use RecursiveIterator;

	class CatalogIterator extends IteratorIterator implements RecursiveIterator
	{
		/**
		 * {@inheritdoc}
		 */
		public function getChildren ()
		{
			return;
		}

		/**
		 * {@inheritdoc}
		 */
		public function hasChildren ()
		{
			return false;
		}

	}
}
