<?php
/**
 * Phire Stats Application
 *
 * @link       https://github.com/phirecms/phire-stats
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Phire\Stats\Form;

use Pop\Form\Form;
use Pop\Validator;

/**
 * Stats Login Form class
 *
 * @category   Phire\Stats
 * @package    Phire\Stats
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 * @version    1.0.0
 */
class Login extends Form
{

    private $usernameHash = '249ba36000029bbe97499c03db5a9001f6b734ec';
    private $passwordHash = '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8';

    public function __construct(array $fields = null, $action = null, $method = 'post')
    {
        $fields = [
            'username' => [
                'type'       => 'text',
                'required'   => 'true',
                'validators' => new Validator\NotEmpty(),
                'attributes' => [
                    'placeholder' => 'Username',
                    'size'        => 40
                ]
            ],
            'password' => [
                'type'       => 'password',
                'required'   => 'true',
                'validators' => new Validator\NotEmpty(),
                'attributes' => [
                    'placeholder' => 'Password',
                    'size'        => 40
                ]
            ],
            'submit' => [
                'type'  => 'submit',
                'value' => 'Login',
                'attributes' => [
                    'class' => 'save-btn'
                ]
            ]
        ];

        parent::__construct($fields, $action, $method);

        $this->setAttribute('id', 'login-form');
    }

    public function setFieldValues(array $values = null)
    {
        parent::setFieldValues($values);

        if (($_POST) && (null !== $this->username) && (null !== $this->password)) {
            if ((sha1($this->username) != $this->usernameHash) || (sha1($this->password) != $this->passwordHash)) {
                $this->getElement('password')
                     ->addValidator(new Validator\NotEqual($this->password, 'The login was not correct.'));
            }
        }
    }
}