<?php

// Declares the namespace for controller classes
namespace App\Controllers;

// Defines the shared base controller class
abstract class Controller
{
    // Stores the model dependency for child controllers
    protected $model = null;

    // Stores the template engine dependency for child controllers
    protected $templateEngine = null;
}
