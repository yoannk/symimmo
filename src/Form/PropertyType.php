<?php

namespace App\Form;

use App\Entity\Property;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'label.title'])
            ->add('description', TextareaType::class, ['label' => 'label.description'])
            ->add('surface', IntegerType::class, ['label' => 'label.surface'])
            ->add('rooms', IntegerType::class, ['label' => 'label.rooms'])
            ->add('bedrooms', IntegerType::class, ['label' => 'label.bedrooms'])
            ->add('floor', IntegerType::class, ['label' => 'label.floor'])
            ->add('price', IntegerType::class, ['label' => 'label.price'])
            ->add('heat', ChoiceType::class, [
                'label' => 'label.heat',
                'choices' => array_flip(Property::HEAT)
            ])
            ->add('tags', EntityType::class, [
                'required' => false,
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('pictureFiles', FileType::class, [
                'required' => false,
                'multiple' => true
            ])
            ->add('city', TextType::class, ['label' => 'label.city'])
            ->add('address', TextType::class, ['label' => 'label.address'])
            ->add('postalCode', TextType::class, ['label' => 'label.postalCode'])
            ->add('lat', HiddenType::class)
            ->add('lng', HiddenType::class)
            ->add('sold', CheckboxType::class, [
                'label' => 'label.sold',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
