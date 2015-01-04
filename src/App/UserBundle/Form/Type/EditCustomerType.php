<?php

namespace App\UserBundle\Form\Type;

use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use App\UserBundle\Form\Extension\ChoiceList\SlaveRoleChoiceList;

class EditCustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, [
                'label' => 'common.username_to_login',
                'required' => true,
            ])
            ->add('name', null, [
                'label' => 'sitebeat.cabinet.slave_name',
                'required' => true,
            ])
            ->add('email', 'email', [
                'label' => 'common.email',
                'required' => true,
            ])
            ->add('oldPlainPassword', 'password', [
                'label' => 'common.password',
                'constraints' => new UserPassword(['message' => 'current.errors.wrong_old_password']),
                'validation_groups' => array('Default'),
                'mapped' => false,
                'required' => true,
            ])
            ->add('plainPassword', 'password', [
                'label' => 'common.password_new',
                'required' => false,
            ])
        ;
    }

    public function getName()
    {
        return 'editCustomer';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\UserBundle\Entity\User',
            'validation_groups'  => ['Profile'],
        ]);
    }
}
