<?php
namespace ZealByte\Catalog
{
	use Symfony\Component\Form\FormTypeInterface;
	use ZealByte\Util;

	class CatalogBuilder implements CatalogBuilderInterface
	{
		const ZDO_FILTER_OPTION_NAME = 'name';
		const ZDO_FILTER_OPTION_TYPE = 'type';
		const ZDO_FILTER_OPTION_OPTIONS = 'options';

		private $formType;

		private $datums = [];


		/**
		 * {@inheritdoc}
		 */
		public function addDatum (DatumInterface $datum) : CatalogBuilderInterface
		{
			array_push($this->datums, $datum);

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getDatum (string $name) : DatumInterface
		{
			if (!$this->hasDatums())
				throw new RuntimeException("Datum $name does not exist!");

			$name = Util\Canonical::name($name);

			foreach ($this->datums as $datum)
				if (Util\Canonical::name($datum->getName()) == $name)
					return $datum;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getDatums () : array
		{
			if (!$this->hasDatums())
				throw new RuntimeException("No datums have been defined!");

			return array_values($this->datums);
		}

		/**
		 * {@inheritdoc}
		 */
		public function hasDatum (string $name) : bool
		{
			$name = Util\Canonical::name($name);

			if ($this->hasDatums())
				foreach ($this->datums as $datum)
					if (Util\Canonical::name($datum->getName()) == $name)
						return true;

			return false;
		}

		/**
		 * {@inheritdoc}
		 */
		public function hasDatums () : bool
		{
			return !empty($this->datums);
		}

	}
}
