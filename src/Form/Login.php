<?php

namespace Phire\Stats\Form;

use Pop\Form\Form;
use Pop\Validator;

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
                    'placeholder' => 'Username'
                ]
            ],
            'password' => [
                'type'       => 'password',
                'required'   => 'true',
                'validators' => new Validator\NotEmpty(),
                'attributes' => [
                    'placeholder' => 'Password'
                ]
            ],
            'submit' => [
                'type'  => 'submit',
                'value' => 'Login'
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