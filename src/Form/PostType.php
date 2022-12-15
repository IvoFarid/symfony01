<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PostType extends AbstractType
{
    // public function __construct(Security $sec, TokenStorageInterface $token){
    //   if($sec->getUser()){
    //     $this->user = $sec->getUser()->getId();
    //     $this->test = $token->getToken()->getUser();
    //   } else {
    //     $this->test = $sec->getUser();
    //   }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   
        $builder
            ->add('title', TextType::class, [
              'required' => true,
              'label' => 'Post Title',
              'attr' => array(
                'class' => 'ps-2 mb-2 w-100 border-0 rounded-3',
                'placeholder'=>'post title...'
              ),
              'label_attr' => [
                'class' => 'd-none'
              ]
            ])
            ->add('description', TextareaType::class, [
              'label' => 'description',
              'attr' => array(
                'class' => 'ps-2 w-100 border-0 rounded-3',
                'placeholder'=>'description...',
                'rows'=>'1'
              ),
              'label_attr' => array(
                'class'=>'d-none'
              )
            ])
            // ->add('author', EntityType::class, [
            //   'class' => User::class ])
            //   // AGREGAR IF POR SI NO HAY NADIE LOGUEADO
            // //   'data' => ($this->test),
            // //   'attr' => array(
            // //     'class' => 'd-none'
            // //   ),
            // //   'label_attr' => array(
            // //     'class' => 'd-none'
            // //   )
            // // ])
            // // //   'attr' => [
            // // //     'value' => $token->getUser()->getId()
            // // //   ]
            // // // ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
