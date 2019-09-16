<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\SyClass\System;
use Facebook\PersistentData\PersistentDataInterface;
/**
 * Description of PersistenceLaravelFacebook
 *
 * @author johnm
 */
class PersistenceLaravelFacebook implements PersistentDataInterface {
    /**
    * @var string Prefix to use for session variables.
    */
    protected $sessionPrefix = 'FBRLH_';

    /**
    * @inheritdoc
    */
    public function get($key)
    {
        return \Session::get($this->sessionPrefix . $key);
    }

    /**
    * @inheritdoc
    */
   public function set($key, $value)
    {
        \Session::put($this->sessionPrefix . $key, $value);
    }
}
