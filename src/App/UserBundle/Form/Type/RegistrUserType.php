<?php

namespace App\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, [
                'label' => 'common.username_to_login',
                'required' => true,
            ])
            ->add('name', null, [
                'label' => 'common.your_name',
                'required' => true,
            ])
            ->add('email', 'email', [
                'label' => 'common.email',
                'required' => true,
            ])
            ->add('plainPassword', 'password', [
                'label' => 'common.password',
                'required' => true,
            ])
        ;
    }

    public function getName()
    {
        return 'registrUser';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\UserBundle\Entity\User',
            'validation_groups'  => ['Registration'],
        ]);
    }
}
