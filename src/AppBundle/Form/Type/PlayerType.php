<?php

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('team', EntityType::class, array(
                'class' => 'AppBundle:Team',
                'choice_label' => 'name',
            ))
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('position', ChoiceType::class, array(
                'choices' => [
                    'On field' => [
                        'Goalkeeper' => 'Goalkeeper',
                        'Defender' => 'Defender',
                        'Midfielder' => 'Midfielder',
                        'Forward' => 'Forward',
                    ],
                    'Coach' => [
                        'Coach' => 'Coach',
                    ]
                ],
                'choices_as_values' => true,
            ))
            ->add('statistic', TextType::class)
            ->add('age', DateType::class, array(
                'input'  => 'datetime',
                'widget' => 'choice',
                'years'  => range(1950, 2010),
            ))
            ->add('biography', TextareaType::class)
            ->add('photo', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Player',
            'em' => null,
        ]);
    }

    public function getBlockPrefix()
    {
        return "team";
    }
}