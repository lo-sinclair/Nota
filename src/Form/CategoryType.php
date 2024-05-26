<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
	        ->add('title')
	        ->add('image', FileType::class, [
		        'label' => 'image',

		        // unmapped means that this field is not associated to any entity property
		        'mapped' => false,

		        // make it optional so you don't have to re-upload the PDF file
		        // every time you edit the Product details
		        'required' => false,

		        // unmapped fields can't define their validation using attributes
		        // in the associated entity, so you can use the PHP constraint classes
		        'constraints' => [
			        new File([
				        'maxSize' => '4096k',
				        'mimeTypes' => [
					        'image/jpeg',
					        'image/png',
				        ],
				        'mimeTypesMessage' => 'Please upload a valid Image file',
			        ])
		        ],
	        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
