<?php

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('championship', ChoiceType::class, array(
                'choices' => [
                    'Euro 2016' => 'Euro 2016',
                ],
                'choices_as_values' => true,
            ))
            ->add('round', ChoiceType::class, array(
                'choices' => [
                    'Play off' => 'Play off',
                ],
                'choices_as_values' => true,
            ))
            ->add('gamedate', DateType::class, array(
                'label'  => 'Date',
                'input'  => 'datetime',
                'widget' => 'choice',
                'years'  => range(2015, 2020),
            ))
            ->add('referee', TextareaType::class)
            ->add('stadium', TextType::class)
            ->add('scores', CollectionType::class, array(
                'entry_type' => GameScoreType::class,
                'by_reference' => false,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Game',
            'em' => null,
        ]);
    }

    public function getBlockPrefix()
    {
        return "game";
    }
}