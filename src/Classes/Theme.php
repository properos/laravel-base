<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Properos\Base\Classes;

/**
 * Description of Helper
 *
 * @author Properos
 */
class Theme
{
    public static function get(){
        return env('THEME', 'default');
    }
}