<?php
/**
 * Navigation - модуль меню
 * Шаблон вывода: default
 *
 * @version 1.0
 * @package Joostina CMS
 * @subpackage Modules
 * @author JoostinaTeam
 * @copyright (C) 2008-2010 Joostina Team
 * @license see license.txt
 *
 * */
//Запрет прямого доступа
defined('_JOOS_CORE') or die();

?>
<?php navigation_ul_li_recurse($menu_items, 1); ?><?php


function navigation_ul_li_recurse(array $items, $level = 1)
{
    ?>
<ul class="dropdown<?php echo $level > 1 ? $level : '' ?>">
    <?php foreach ($items as $item => $data) : ?>
    <?php $_has_children = (isset($data['children']) && is_array($data['children'])) ? 1 : 0 ?>
    <?php $_access_allow = (isset($data['access']) && !in_array(joosCore::user()->gid, $data['access'])) ? 0 : 1 ?>

    <?php if ($_access_allow): ?>
        <?php $_rel = str_replace(JPATH_SITE, '', $data['href']); ?>
        <li>
					<span rel="<?php echo $_rel ?>" <?php echo $_has_children ? ' class="parent switch_ac"' : ' class="switch_ac"' ?>>
						<?php echo $data['href'] ? sprintf('<a href="%s" title="%s">%s</a>', (strpos($data['href'], 'http:') !== false ? null : JPATH_SITE) . $data['href'], ((isset($data['title']) && isset($data['title']{1})) ? $data['title'] : $item), $item) : $item ?>
					</span>
            <?php $_has_children ? navigation_ul_li_recurse($data['children'], $level + 1) : null; ?>
        </li>
        <?php endif; ?>

    <?php endforeach; ?>
</ul>
<?php

}

