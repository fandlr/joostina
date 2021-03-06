<?php
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosBenchmark - Библиотека работы с "хлебными крошками"
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
class joosBreadcrumbs {

	private static $instance;
	private static $breadcrumbs = array();

	/**
	 *
	 * @return joosBreadcrumbs
	 */
	public static function instance() {
		if (self::$instance === NULL) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function add($name, $href = false) {
		$name = __($name);
		self::$breadcrumbs[] = $href ? joosHtml::anchor($href, $name, array('class' => 'breadcrumbs_link', 'title' => $name)) : $name;
		return $this;
	}

	public function remove($index = false, $name = false) {
		return $this;
	}

	public function get() {
		// поисковикам очень хорошо подсказать что это крошки, и использовать значек › - поисковики его любят ( проштудировал кучу ссылок )
		return '<div class="breadcrumbs">' . implode(' › ', self::$breadcrumbs) . '</div>';
	}

	public function get_breadcrumbs_array() {
		return self::$breadcrumbs;
	}

	// переопределим ВСЕ установленные вручную title, и сформируем их из "хлебных крошек"
	public static function add_to_title() {
		joosDocument::instance()
				->set_page_title(strip_tags(implode(' › ', self::$breadcrumbs)));
	}

}