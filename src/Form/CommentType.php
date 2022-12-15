<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class CommentType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('body', TextType::class, [
              'attr' => [
                'placeholder'=>'add comment...',
                'class' => 'bg-white outline-none border w-100 mb-2 rounded-3 py-2'
              ],
              'label_attr' => array(
                       'class' => 'd-none'
              )
            ]);
        //     ->add('author', EntityType::class, [
        //       'class' => User::class,
        //       'attr' => array(
        //         'class' => 'd-none'
        //       ),
        //       'label_attr' => array(
        //         'class' => 'd-none'
        //       ),
        //       'data' => $this->test
        //     ])
        //     ->add('postId', EntityType::class, [
        //     'class' => Post::class,
        //     'attr' => array(
        //       'class' => 'd-none'
        //     ),
        //     'label_attr' => array(
        //       'class' => 'd-none'
        //     )
        //     ])
        // ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
