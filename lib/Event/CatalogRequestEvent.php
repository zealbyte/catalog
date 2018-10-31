<?php
namespace ZealByte\Catalog\Event
{
	use Symfony\Component\HttpFoundation\Request;

	class CatalogRequestEvent extends CatalogEvent implements CatalogRequestEventInterface
	{
		private $request;


		/**
		 *
		 */
		public function getRequest () : Request
		{
			return $this->request;
		}

		/**
		 *
		 */
		public function hasRequest () : bool
		{
			return ($this->request) ? true : false;
		}

		/**
		 *
		 */
		public function setRequest (Request $request) : CatalogRequestEventInterface
		{
			$this->request = $request;

			return $this;
		}

	}
}
