<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosHit - Библиотека работы со счетчиками хранимыми в базе данных
 * Системная библиотека
 *
 * @version 1.0
 * @package Joostina.Libraries
 * @subpackage Libraries
 * @category Libraries
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosHit {

	/**
	 * Добавление / увеличение счетчика хитов
	 * @param string $option название компонента
	 * @param integer $id - идентификатор объекта
	 * @param string $task - подзадача
	 * @return boolean результат увеличения счетчика
	 */
	public static function add($option, $id, $task = '') {
		$sql = sprintf("INSERT INTO `#__hits` (`id`, `obj_id`, `obj_option`, `obj_task`, `hit`) VALUES (NULL, %s, '%s', '%s', 1)
									ON DUPLICATE KEY UPDATE hit=hit+1;", (int) $id, $option, $task);
		return joosDatabase::instance()->set_query($sql)->query();
	}

	/**
	 * Добавление / увеличение счетчика хитов по объекту
	 * @param stdClass $obj объект который, обращения к которому необходимо подсчитать
	 * @param  string $task название задачи выполняемой над объектов
	 * @return  bool результат добвления / увеличения счетчика
	 */
	public static function add_obj(stdClass $obj, $task = '') {

		$class_name = get_class($obj);
		$id = crc32($class_name);
		$option = $class_name;

		$sql = sprintf("INSERT INTO `#__hits` (`id`, `obj_id`, `obj_option`, `obj_task`, `hit`) VALUES (NULL, %s, '%s', '%s', 1)
									ON DUPLICATE KEY UPDATE hit=hit+1;", (int) $id, $option, $task);
		return joosDatabase::instance()->set_query($sql)->query();
	}

}