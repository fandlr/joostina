<?php

/**
 * Автозагрузчик классов
 *
 * @package Joostina.Core
 * @author JoostinaTeam
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @version SVN: $Id: joostina.php 238 2011-03-13 13:24:57Z LeeHarvey $
 * Иинформация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Автозагрузчик классов
 * @sinletone
 */
class joosAutoloader {

	/**
	 * Массив предустановленных системных библиотек
	 * @var array
	 */
	private static $_static_files = array(
		// проверенные библиотеки
		'joosAcl' => 'core/libraries/acl.php',
		'joosArray' => 'core/libraries/array.php',
		'joosAttached' => 'core/libraries/attached.php',
		'joosBenchmark' => 'core/libraries/benchmark.php',
		'joosBreadcrumbs' => 'core/libraries/breadcrumbs.php',
		'joosCache' => 'core/libraries/cache.php',
		'joosConfig' => 'core/libraries/config.php',
		'joosDatabase' => 'core/libraries/database.php',
		'joosModel' => 'core/libraries/database.php',
		'joosDateTime' => 'core/libraries/datetime.php',
		'joosDebug' => 'core/libraries/debug.php',
		'joosEditor' => 'core/libraries/editor.php',
		'joosFile' => 'core/libraries/file.php',
		'joosFlashMessage' => 'core/libraries/flashmessage.php',
		'joosHit' => 'core/libraries/hit.php',
		'joosHTML' => 'core/libraries/html.php',
		'joosImage' => 'core/libraries/image.php',
		'joosInputFilter' => 'core/libraries/inputfilter.php',
		'joosAutoAdmin' => 'core/libraries/autoadmin.php',
		'joosMetainfo' => 'core/libraries/metainfo.php',
		'joosNestedSet' => 'core/libraries/nestedset.php',
		'joosPager' => 'core/libraries/pager.php',
		'joosParams' => 'core/libraries/params.php',
		'joosRequest' => 'core/libraries/request.php',
		'joosRoute' => 'core/libraries/route.php',
		'joosSession' => 'core/libraries/session.php',
		'joosSpoof' => 'core/libraries/spoof.php',
		'joosString' => 'core/libraries/string.php',
		'joosText' => 'core/libraries/text.php',
		'joosTrash' => 'core/libraries/trash.php',
		'joosVersion' => 'core/libraries/version.php',
		// системные модели
		//'User' => 'app/components/users/models/model.users.php',
		// Это старьё, надо переписать либо удалить
		'htmlTabs' => 'core/libraries/html.php',
		'forms' => 'core/vendors/forms/forms.php',
		// Библиотеки сторонних вендоров
		'JJevix' => 'core/vendors/text/jevix/jevix.php',
	);
	private static $_debug = true;

	public static function init() {
		spl_autoload_register(array(new self, 'autoload'));
	}

	private function __clone() {
		
	}

	public static function autoload($class) {

		$file = $class . '.php';

		// первый шаг - ищем класс в жестко прописанных параметрах
		if (isset(self::$_static_files[$class])) {
			$file = JPATH_BASE . DS . self::$_static_files[$class];
		} else {
			$file = JPATH_BASE . DS . self::get_class_dinamic_path($class);
		}

		if (!is_file($file)) {
			throw new AutoloaderFileNotFoundException(sprintf(__('Автозагрузчик классов не смог обнаружить предпологаемый файл %s файл для класса %s'), $file, $class));
		}

		require_once $file;

		if (!class_exists($class, false)) {
			throw new AutoloaderClassNotFoundException(sprintf(__('Автозагрузчик классов не смог найти требуемый класс %s в предпологаемом файле %s'), $class, $file));
		}
		!self::$_debug ? : joosDebug::add(sprintf(__('Автозагрузка класса %s из файла %s'), $class, $file));

		unset($file);
	}

	private static function get_class_dinamic_path($class) {

		// если в названии класса есть _ - то это подласс в общем файле класса модели
		if (strpos($class, '_') > 0) {
			$class_names = explode('_', $class);
			$class = $class_names[0];
		}


		$file = '';
		if (strpos($class, 'admin', 0) === 0) {
			$name = str_replace('admin', '', $class);
			$name = strtolower($name);
			$file = 'app' . DS . 'components' . DS . $name . DS . 'models' . DS . 'model.admin.' . $name . '.php';
		} elseif (strpos($class, 'actions', 0) === 0) {
			$name = str_replace('actions', '', $class);
			$name = strtolower($name);
			$file = 'app' . DS . 'components' . DS . $name . DS . 'controller.' . $name . '.php';
		} elseif (strpos($class, 'joos', 0) === 0) {
			$name = str_replace('joos', '', $class);
			$name = strtolower($name);
			$file = 'core' . DS . 'libraries' . DS . $name . '.php';
		} else {
			$name = strtolower($class);
			$file = 'app' . DS . 'components' . DS . $name . DS . 'models' . DS . 'model.' . $name . '.php';
		}

		return $file;
	}

	/**
	 * Преобразование названия вложенной модели
	 * Требуется для определения файла содержащего множественный модели единого контроллера
	 * @example UserGroops => User_Groops
	 * 
	 * @param type $string
	 * @return string
	 */
	private static function underscore($string) {
		return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $string));
	}

	/**
	 * Предстартовая загрузка необходимого списка 
	 * 
	 * @example joosAutoloader( array('text','array','acl') )
	 * 
	 * @param array $names
	 * @return void
	 */
	public static function libraries_load_on_start(array $names = array()) {

		foreach ($names as $name) {

			$file = JPATH_BASE . DS . 'core' . DS . 'libraries' . DS . $name . '.php';

			if (!is_file($file)) {
				throw new AutoloaderOnStartFileNotFoundException(sprintf(__('Автозагрузчки не смог найти файл %s для автозагружаемой библиотеки %'), $file, $name));
			}

			require_once ($file);
		}
	}

}

/**
 * Обработка исключений отсутствующих классов при работе автозагрузчика
 */
class AutoloaderFileNotFoundException extends joosException {
	
}

/**
 * Обработка исключений отсутсвия нужных классов в найденных файлах автозагрузчика
 */
class AutoloaderClassNotFoundException extends joosException {
	
}

/**
 * Обработка исключений отсутствующих классов при работе автозагрузчика
 */
class AutoloaderOnStartFileNotFoundException extends joosException {
	
}