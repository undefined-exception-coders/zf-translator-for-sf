<?php

use Interop\Container\ContainerInterface;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use UEC\ZendFrameworkTranslatorForSymfony\Translator;
use UEC\ZendFrameworkTranslatorForSymfony\TranslatorHelper;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

return [
    'service_manager' => [
        'factories' => [
            \UEC\Standalone\Symfony\Form\FormBuilderFactory::class => function(ContainerInterface $services) {
                $factory = new \UEC\Standalone\Symfony\Form\FormBuilderFactory;
                return $factory();
            },
            \UEC\Standalone\Symfony\Form\ValidationBuilderFactory::class => function(ContainerInterface $services) {
                $factory = new \UEC\Standalone\Symfony\Form\ValidationBuilderFactory;
                return $factory();
            },

            \UEC\Standalone\Symfony\Form\FormHelperFactory::class => function(ContainerInterface $services) {
                $engineFactory = new \UEC\Standalone\Symfony\Form\Template\PhpEngineFactory([
                    \UEC\Standalone\Symfony\Form\Template\Template::$BOOTSTRAP3,
                ]);
                $engine = $engineFactory();
                $formHelperFactory = new \UEC\Standalone\Symfony\Form\FormHelperFactory($engine);
                $formHelper = $formHelperFactory();

                $engine->set($formHelper);
                $engine->set($services->get(TranslatorHelper::class));

                return $formHelper;
            },
            Translator::class => function (ServiceLocatorInterface $services) {
                return new Translator($services->get(TranslatorInterface::class), 'it_IT');
            },
            TranslatorHelper::class => function (ServiceLocatorInterface $services) {
                $bridgeTranslator = $services->get(Translator::class);
                return new TranslatorHelper($bridgeTranslator);
            },
            'form' => function (ServiceLocatorInterface $services) {
                return $services->get('form_factory_builder')->getFormFactory();
            },
            'form_factory_builder' => function (ServiceLocatorInterface $services) {
                $builder = $services->get(\UEC\Standalone\Symfony\Form\FormBuilderFactory::class);
                $validation = $services->get(\UEC\Standalone\Symfony\Form\ValidationBuilderFactory::class);

                $validation->setTranslator($services->get(Translator::class));
                $validation->setTranslationDomain('validators');

                $builder->addExtension(new ValidatorExtension($validation->getValidator()));
                $builder->addExtension(new CsrfExtension(new CsrfTokenManager()));

                return $builder;
            },
        ],
        'aliases' => [
            'form_helper' => \UEC\Standalone\Symfony\Form\FormHelperFactory::class
        ]
    ],
];