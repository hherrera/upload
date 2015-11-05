<?php

/*
 * This file is part of the Silex framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Silex\Tests\Provider;

use Silex\Application;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\FormServiceProvider;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Validator\Constraints as Assert;
use Silex\Tests\Provider\ValidatorServiceProviderTest\Constraint\Custom;
use Silex\Tests\Provider\ValidatorServiceProviderTest\Constraint\CustomValidator;

/**
 * ValidatorServiceProvider.
 *
 * Javier Lopez <f12loalf@gmail.com>
 */
class ValidatorServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testRegister()
    {
        $app = new Application();

        $app->register(new ValidatorServiceProvider());

        return $app;
    }

    public function testRegisterWithCustomValidators()
    {
        $app = new Application();

        $app['custom.validator'] = $app->share(function () {
            return new CustomValidator();
        });

        $app->register(new ValidatorServiceProvider(), array(
            'validator.validator_service_ids' => array(
                'test.custom.validator' => 'custom.validator',
            ),
        ));

        return $app;
    }

    /**
     * @depends testRegisterWithCustomValidators
     */
    public function testConstraintValidatorFactory($app)
    {
        $this->assertInstanceOf('Silex\ConstraintValidatorFactory', $app['validator.validator_factory']);

        $validator = $app['validator.validator_factory']->getInstance(new Custom());
        $this->assertInstanceOf('Silex\Tests\Provider\ValidatorServiceProviderTest\Constraint\CustomValidator', $validator);
    }

    /**
     * @depends testRegister
     */
    public function testConstraintValidatorFactoryWithExpression($app)
    {
        if (!class_exists('Symfony\Component\Validator\Constraints\Expression')) {
            $this->markTestSkipped('Expression are not supported by this version of Symfony');
        }

        $constraint = new Assert\Expression('true');
        $validator = $app['validator.validator_factory']->getInstance($constraint);
        $this->assertInstanceOf('Symfony\Component\Validator\Constraints\ExpressionValidator', $validator);
    }

    /**
     * @depends testRegister
     */
    public function testValidatorServiceIsAValidator($app)
    {
        $this->assertInstanceOf('Symfony\Component\Validator\ValidatorInterface', $app['validator']);
    }

    /**
     * @depends testRegister
<<<<<<< HEAD
     * @dataProvider getTestValidatorConstraintProvider
=======
     * @dataProvider testValidatorConstraintProvider
>>>>>>> c4ca7ef1998f7d27d3aa2057ee37bc1da48e629a
     */
    public function testValidatorConstraint($email, $isValid, $nbGlobalError, $nbEmailError, $app)
    {
        $app->register(new ValidatorServiceProvider());
        $app->register(new FormServiceProvider());

        $constraints = new Assert\Collection(array(
            'email' => array(
                new Assert\NotBlank(),
                new Assert\Email(),
            ),
        ));

<<<<<<< HEAD
        $builder = $app['form.factory']->createBuilder(class_exists('Symfony\Component\Form\Extension\Core\Type\RangeType') ? 'Symfony\Component\Form\Extension\Core\Type\FormType' : 'form', array(), array(
=======
        $builder = $app['form.factory']->createBuilder('form', array(), array(
>>>>>>> c4ca7ef1998f7d27d3aa2057ee37bc1da48e629a
            'constraints' => $constraints,
            'csrf_protection' => false,
        ));

        $form = $builder
<<<<<<< HEAD
            ->add('email', class_exists('Symfony\Component\Form\Extension\Core\Type\RangeType') ? 'Symfony\Component\Form\Extension\Core\Type\EmailType' : 'email', array('label' => 'Email'))
=======
            ->add('email', 'email', array('label' => 'Email'))
>>>>>>> c4ca7ef1998f7d27d3aa2057ee37bc1da48e629a
            ->getForm()
        ;

        $form->submit(array('email' => $email));

        $this->assertEquals($isValid, $form->isValid());
        $this->assertEquals($nbGlobalError, count($form->getErrors()));
        $this->assertEquals($nbEmailError, count($form->offsetGet('email')->getErrors()));
    }

    public function testValidatorWillNotAddNonexistentTranslationFiles()
    {
        $app = new Application(array(
            'locale' => 'nonexistent',
        ));

        $app->register(new ValidatorServiceProvider());
        $app->register(new TranslationServiceProvider(), array(
            'locale_fallbacks' => array(),
        ));

        $app['validator'];
        $translator = $app['translator'];

        try {
            $translator->trans('test');
        } catch (NotFoundResourceException $e) {
            $this->fail('Validator should not add a translation resource that does not exist');
        }
    }

<<<<<<< HEAD
    public function getTestValidatorConstraintProvider()
    {
        // Email, form is valid, nb global error, nb email error
=======
    public function testValidatorConstraintProvider()
    {
        // Email, form is valid , nb global error, nb email error
>>>>>>> c4ca7ef1998f7d27d3aa2057ee37bc1da48e629a
        return array(
            array('', false, 0, 1),
            array('not an email', false, 0, 1),
            array('email@sample.com', true, 0, 0),
        );
    }

<<<<<<< HEAD
    /**
     * @dataProvider getAddResourceData
     */
    public function testAddResource($registerValidatorFirst)
=======
    public function testAddResource()
>>>>>>> c4ca7ef1998f7d27d3aa2057ee37bc1da48e629a
    {
        $app = new Application();
        $app['locale'] = 'fr';

        $app->register(new ValidatorServiceProvider());
        $app->register(new TranslationServiceProvider());
        $app['translator'] = $app->share($app->extend('translator', function ($translator, $app) {
            $translator->addResource('array', array('This value should not be blank.' => 'Pas vide'), 'fr', 'validators');

            return $translator;
        }));

<<<<<<< HEAD
        if ($registerValidatorFirst) {
            $app['validator'];
        }
=======
        $app['validator'];
>>>>>>> c4ca7ef1998f7d27d3aa2057ee37bc1da48e629a

        $this->assertEquals('Pas vide', $app['translator']->trans('This value should not be blank.', array(), 'validators', 'fr'));
    }

<<<<<<< HEAD
    public function getAddResourceData()
    {
        return array(array(false), array(true));
    }

=======
>>>>>>> c4ca7ef1998f7d27d3aa2057ee37bc1da48e629a
    public function testAddResourceAlternate()
    {
        $app = new Application();
        $app['locale'] = 'fr';

        $app->register(new ValidatorServiceProvider());
        $app->register(new TranslationServiceProvider());
        $app->extend('translator.resources', function ($resources, $app) {
            $resources = array_merge($resources, array(
                array('array', array('This value should not be blank.' => 'Pas vide'), 'fr', 'validators'),
            ));

            return $resources;
        });

        $app['validator'];

        $this->assertEquals('Pas vide', $app['translator']->trans('This value should not be blank.', array(), 'validators', 'fr'));
    }
}
