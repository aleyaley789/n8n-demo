<?php

namespace App\Form;

use App\Entity\SupportTicket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupportTicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Benutzername',
                'attr' => [
                    'placeholder' => 'Dein Benutzername',
                    'autocomplete' => 'username',
                ],
            ])
            ->add('beschreibung', TextareaType::class, [
                'label' => 'Beschreibung',
                'attr' => [
                    'placeholder' => 'Beschreibe dein Problem so genau wie möglich...',
                    'rows' => 6,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SupportTicket::class,
            'csrf_protection' => false,
        ]);
    }
}
