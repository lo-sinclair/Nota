<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\Status;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->add('status', EnumType::class, [
			'class' => Status::class,
			'label' => 'Редактировать статус: ',
		]);
	}


	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Order::class,
		]);
	}

}