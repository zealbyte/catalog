<?php
namespace ZealByte\Catalog\Event
{
	use Symfony\Component\HttpFoundation\Request;

	interface CatalogRequestEventInterface extends CatalogEventInterface
	{
		/**
		 *
		 */
		public function getRequest () : Request;

		/**
		 *
		 */
		public function hasRequest () : bool;

		/**
		 *
		 */
		public function setRequest (Request $request) : CatalogRequestEventInterface;
	}
}
