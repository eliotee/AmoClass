<?
require_once(__DIR__.'/DBClass.php');
$DBconnection = new DataBase;
$DBconnection->CreateTable();
var_dump($DBconnection->GetUserInfoById(1));
$DBconnection->CreateUser('Anton', 'Anton@mail.com');
$fields = ['name' => 'Oleg', 'email' => 'Oleg@mail.com'];
var_dump($DBconnection->GetUserInfoById($DBconnection->ChangeUserInfo(2, $fields)));
