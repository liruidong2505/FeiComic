<?php

require_once 'application/modles/Dictdef.php';
class Myclass { //创建一个自定义类
	public static function getDictdef($dictid, $isOrderSort) {
		$dictdef = new Dictdef ();
		$array = array ();
		foreach ( $dictdef->getDictlistById ( $dictid, $isOrderSort ) as $value ) {
			$array [] = array ('desc' => $value['dict_desc'], 'code' => $value['dict_code'] );
		}
		return $array;
	}

}
