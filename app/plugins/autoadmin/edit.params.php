<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * autoadminEditParams - расширение joosAutoAdmin для вывода элементов параметров joosParams
 * Базовый плагин
 *
 * @version 1.0
 * @package Joostina.Plugins
 * @subpackage Plugins
 * @category joosAutoAdmin
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class autoadminEditParams {

	public static function render($element_param, $key, $value, $obj_data, $params, $tabs) {
		$element = array();

		$data = (isset($element_param['html_edit_element_param']['call_from']) && is_callable($element_param['html_edit_element_param']['call_from'])) ? call_user_func($element_param['html_edit_element_param']['call_from'], $obj_data) : null;

		if (!$data) {
			return;
		}

		$element[] = $params['label_begin'];
		$element[] = forms::label(
						array(
					'for' => $key
						), (isset($element_param['html_edit_element_param']['text']) ? $element_param['html_edit_element_param']['text'] : $element_param['name']));

		$element[] = $params['label_end'];
		$element[] = $params['el_begin'];

		$main_key = $key;
		$values = $obj_data->$main_key;

		$element[] = '<table class="admin_params">';
		foreach ($data as $key => $field) {
			if (isset($field['editable']) && $field['editable'] == true) {
				$v = isset($values[$key]) ? $values[$key] : '';
				$element[] = joosAutoAdmin::get_edit_html_element($field, $main_key . '[' . $key . ']', $v, $obj_data, $params, $tabs);
			}
		}
		$element[] = '</table>';

		$element[] = $params['el_end'];

		return implode("\n", $element);
	}

}