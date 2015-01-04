<?php

namespace App\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use App\UserBundle\Form\Extension\ChoiceList\SlaveRoleChoiceList;

class SlaveType extends AbstractType
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
            ->add('plainPassword', 'password', [
                'label' => 'common.password',
                'required' => true,
            ])
            ->add('myRole', 'choice', [
                'label' => 'sitebeat.cabinet.role',
                'choice_list' => new SlaveRoleChoiceList(),
            ])
            ->add('isolated', 'checkbox', [
                'label' => 'sitebeat.cabinet.isolated',
                'required' => false,
                'attr' => ['tooltip-able' => 1, 'title' => 'sitebeat.cabinet.isolated_tip'],
            ])
            ->add('send_email', 'checkbox', [
                'label' => 'sitebeat.cabinet.send_notify_on_create',
                'mapped' => false,
                'required' => false,
                'attr' => ['tooltip-able' => 1, 'title' => 'sitebeat.cabinet.send_notify_on_create_tip'],
            ])
        ;
    }

    public function getName()
    {
        return 'createSlave';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\UserBundle\Entity\User',
            'validation_groups'  => ['Registration'],
        ]);
    }
}
