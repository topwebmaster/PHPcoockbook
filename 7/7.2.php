<?php
//Определение конструктора объекта
/**
 * Задача состоит в том, чтобы определить метод который будет отрабатывать при создании
 *К примеру, надо автоматически загружать выборку базы данных в момент создания нового эк-
 * земпляра класса.
 */



class Man{
    public $name;

    private function check_user(){
        //какое-то действие
    }

    function __construct($name, $password){
        if($this->check_user($name, $password)){
            $this->name = $name;
        }

    }
}

$man = new Man('Вася','васинпароль');