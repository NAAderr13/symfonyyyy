<?php

namespace App\Form;

use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Emprunt2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateEmprunt', null, [
                'widget' => 'single_text',
            ])
            ->add('dateRetour', null, [
                'widget' => 'single_text',
            ])
            ->add('livre', EntityType::class, [
                'class' => Livre::class,
                'choice_label' => 'id',
            ])
            ->add('utilisateur', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}
