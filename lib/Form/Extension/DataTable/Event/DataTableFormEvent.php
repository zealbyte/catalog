<?php
namespace ZealByte\Catalog\Form\Extension\DataTable\Event
{
	use Symfony\Component\Form\FormInterface;
	use ZealByte\Catalog\Event\CatalogRequestEvent;
	use ZealByte\Catalog\Form\Extension\DataTable\Type\DataTableType;

	class DataTableFormEvent extends CatalogRequestEvent
	{
		private $form;

		public function getForm () : FormInterface
		{
			return $this->form;
		}

		public function hasForm () : bool
		{
			return ($this->form) ? true : false;
		}

		public function setForm (FormInterface $form) : self
		{
			$formType = $form->getConfig()->getType()->getInnerType();

			if (!($formType instanceof DataTableType))
				throw new \Exception("DataTableFormEvent expects form type of ".DataTableType::class." got ".get_class($formType).".");

			$this->form = $form;

			return $this;
		}

	}
}
