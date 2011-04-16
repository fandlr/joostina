<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * На основе DooPager class file.
 *
 * @author Leng Sheng Hong <darkredz@gmail.com>
 * @link http://www.doophp.com/
 * @copyright Copyright &copy; 2009 Leng Sheng Hong
 * @license http://www.doophp.com/license
 */
class Pager
{
    /**
     * CSS class name for the Pages links
     * @var string
     */
    public $pagesCss = 'paginate';

    /**
     * CSS class name for the Pages DropDown menu
     * @var string
     */
    public $dropDownCss = 'pagerDropDown';

    /**
     * CSS class name for the page sizes menu
     * @var string
     */
    public $pageSizeCss = 'pageSize';

    /**
     * CSS class name for inactive links
     * @var string
     */
    public $inactivePrevCss = 'inactivePrev';

    /**
     * CSS class name for inactive links
     * @var string
     */
    public $inactiveNextCss = 'inactiveNext';

    /**
     * CSS class name for selected current link
     * @var string
     */
    public $currentCss = 'active';

    /**
     * CSS class name for next link
     * @var string
     */
    public $nextCss = 'next';

    /**
     * CSS class name for previous link
     * @var string
     */
    public $prevCss = 'prev';


    /**
     * Contain the list of components to be used in view. (pages, jump menu, page_size, current_page, total_page)
     * @var array
     */
    public $components;

    /**
     * Items to be displayed per page
     * @var int
     */
    public $itemPerPage = 10;

    /**
     * The current page number
     * @var int
     */
    public $currentPage = 1;

    /**
     * Maximum Pager length
     * @var int
     */
    public $maxLength = 10;

    /**
     * Total items to be split in the pagination
     * @var int
     */
    public $totalItem;

    /**
     * Total pages in the pagination
     * @var int
     */
    public $totalPage;


    /**
     * The pages HTML output
     * @var string
     */
    public $output;

    /**
     * The URL prefix for all the pagination links
     * @var string
     */
    public $baseUrl;

    //----- for SQL use -----
    /**
     * Position of the record to begin the pagination LIMIT query
     * @var string
     */
    public $low;

    /**
     * Position of the record to end the pagination LIMIT query
     * @var string
     */
    public $high;

    /**
     * To be use with the pagination LIMIT query LIMIT $limit
     * @var string
     */
    public $limit;

    public $offset;

    /**
     * To display/hide the next/prev button links
     * @var bool
     */
    private $_noNextPrev = false;

    /**
     * Default css for testing
     * @var string
     */
    public $defaultCss = '<style>
.paginate, .current, .inactivePrev, .inactiveNext, .prev, .next {
    font-family: Verdana,Helvetica,Arial,sans-serif;
    font-size:12px;
    border:1px solid #D8D8D8;
    float:left;
    height:20px;
    line-height:20px;
    margin-right:2px;
    overflow:hidden;
    padding:0 6px;
    text-decoration:none;
}
a:hover .paginate{
    border-color:#006699;
}

.current {
    border-color:#006699;
    background-color:#006699;
    color:#fff;
    font-weight:bold;
}
.inactivePrev, .inactiveNext{
    color:#999;
}
</style>';

    /**
     * Instanciate the Pager object
     *
     * @param string $baseURL Base URL to be appended to the page number
     * @param int $totalItems Total items to be paginate
     * @param int $itemPerPage Items to be shown in one page.
     * @param int $maxlength Number of links for the pager navigation
     * @param string $prevText joosText for the Previous button link
     * @param string $nextText joosText for the Next button link
     */
    function  __construct($baseURL = '', $totalItems = 120, $itemPerPage = 10, $maxlength = 11, $extraURL = '')
    {
        $this->baseUrl = $baseURL;
        $this->baseUrl = $extraURL ? $this->baseUrl . $extraURL : $this->baseUrl;
        $this->totalItem = $totalItems;
        $this->maxLength = $maxlength;
        $this->itemPerPage = $itemPerPage;
    }

    /**
     * Hide next & prev links from the output
     */
    public function noNextPrev()
    {
        $this->_noNextPrev = true;
    }

    /**
     * Set the CSS class name for the pager components
     *
     * @param string $pagesName
     * @param string $inactiveName
     * @param string $currentName
     * @param string $nextName
     * @param string $prevName
     * @param string $dropDownName
     * @param string $pageSizeName
     */
    public function setCss($pagesName = 'paginate', $inactiveName = 'inactive', $currentName = 'current', $nextName = 'next', $prevName = 'prev', $dropDownName = 'pagerDropDown', $pageSizeName = 'pageSize')
    {
        $this->pagesCss = $pagesName;
        $this->inactiveCss = $inactiveName;
        $this->currentCss = $currentName;
        $this->nextCss = $nextName;
        $this->prevCss = $prevName;
        $this->dropDownCss = $dropDownName;
        $this->pageSizeCss = $pageSizeName;
    }

    /**
     * Paginate the list of items and prepare pager components to be use in View.
     *
     * @param int $page The current page number
     * @param int $itemPerPage Items per page
     * @return array An array of pager component, access via <strong>pages, jump_menu, page_size, current_page, total_page, next_link, prev_link</strong>
     */
    public function paginate($page, $itemPerPage = 0)
    {

        if ($itemPerPage === 0) {
            $itemPerPage = $this->itemPerPage;
        } else {
            $this->itemPerPage = $itemPerPage;
        }

        $this->totalPage = ceil($this->totalItem / $itemPerPage);

        $this->currentPage = (int)$page;

        if ($this->currentPage < 1 || !is_numeric($this->currentPage))
            $this->currentPage = 1;

        if ($this->currentPage > $this->totalPage)
            $this->currentPage = $this->totalPage;

        $prev_page = $this->currentPage - 1;
        $next_page = $this->currentPage + 1;

        $this->_output .= '';

        //-----------------------------------------------------------------------------------------------"НАЗАД"
        $_prev = ($this->currentPage != 1) ?
            //Ссылка
                "<a class=\"page_left\" href=\"{$this->baseUrl}/page/{$prev_page}\">Предыдущие</a>"
                :
            //Текст
                "<b class=\"page_left\">Предыдущие</b>";
        $this->components['prev'] = $_prev;


        //Вывод джампером (с разрывом, если кол-во страниц > допустимого интервала)
        if ($this->totalPage > $this->maxLength) {
            $midRange = $this->maxLength - 2;

            $start_range = $this->currentPage - floor($midRange / 2);
            $end_range = $this->currentPage + floor($midRange / 2);

            if ($start_range <= 0) {
                $end_range += abs($start_range) + 1;
                $start_range = 1;
            }

            if ($end_range > $this->totalPage) {
                $start_range -= $end_range - $this->totalPage;
                $end_range = $this->totalPage;
            }

            while ($end_range - $start_range + 1 < $this->maxLength - 1) {
                $end_range++;
            }

            $modulus = (int)$this->maxLength % 2 == 0;
            $center = floor($this->maxLength / 2);

            if ($this->currentPage > $center) {
                $end_range--;
            }

            if ($modulus == 0 && $this->totalPage - $this->currentPage + 1 <= $center) {
                while ($end_range - $start_range + 1 < $this->maxLength - 1) {
                    $start_range--;
                }
            }
            $range = range($start_range, $end_range);

            for ($i = 1; $i <= $this->totalPage; $i++) {
                // loop through all pages. if first, last, or in range, display
                if ($i == 1 || $i == $this->totalPage || in_array($i, $range)) {
                    $lastDot = '';

                    if ($modulus == 1) {
                        if ($i == $this->totalPage && $this->currentPage < ($this->totalPage - ($this->maxLength - $center - $modulus)))
                            $lastDot = '...';
                    } else {
                        if ($i == $this->totalPage && $this->currentPage <= ($this->totalPage - ($this->maxLength - $center)))
                            $lastDot = '...';
                    }

                    //-----------------------------------------------------------------------------НОМЕРА СТРАНИЦ
                    $_number = ($i == $this->currentPage) ?
                            "<b>$i"
                            :
                            "<a href=\"{$this->baseUrl}/page/$i\">$lastDot $i";


                    if ($range[0] > 2 && $i == 1) {
                        $_number .= " ...</a>";
                    }

                    else {
                        $_number .= ($i == $this->currentPage) ? '</b>' : '</a>';
                    }

                    $this->_output .= $_number;
                }
            }


            //-----------------------------------------------------------------------------"ВПЕРЕД"
            $_next = ($this->currentPage != $this->totalPage && $this->totalItem >= $this->maxLength) ?
                //Ссылка
                    "<a class=\"page_right\"  href=\"{$this->baseUrl}/page/$next_page\">Следующие</a>"
                    :
                //Текст
                    "<b class=\"page_right\">Следующие</b>";
            $this->components['next'] = $_next;

        }


        else {
            //-----------------------------------------------------------------------------НОМЕРА СТРАНИЦ
            for ($i = 1; $i <= $this->totalPage; $i++) {
                $_number = ($i == $this->currentPage) ?
                        "<b>$i</b>"
                        :
                        "<a href=\"{$this->baseUrl}/page/$i\">$i</a>";

                $this->_output .= $_number;
            }

            //-----------------------------------------------------------------------------"ВПЕРЕД"
            $_next = ($this->currentPage != $this->totalPage) ?
                //Ссылка
                    "<a class=\"page_right\"  href=\"{$this->baseUrl}/page/$next_page\">Следующие</a>"
                    :
                //Текст
                    "<b class=\"page_right\">Следующие</b>";
            $this->components['next'] = $_next;
        }


        $this->low = ($this->currentPage - 1) * $this->itemPerPage;
        $this->high = ($this->currentPage * $this->itemPerPage) - 1;
        $this->low = $this->low < 0 ? 0 : $this->low;
        $this->limit = $this->itemPerPage;
        $this->offset = $this->low;


        $this->components['jump_menu'] = $this->showJumpMenu();
        $this->components['current_page'] = $this->currentPage;
        $this->components['total_page'] = $this->totalPage;

        /*
          $this->output = '	<div class="pagination">
                                  <ul class="pagenav_list pagenav_next-prev listreset">
                                      <li class="pagenav_list_item pagenav_prev">'.$this->components['prev'].'</li>
                                      <li class="pagenav_list_item pagenav_next">'.$this->components['next'].'</li>
                                  </ul>

                                  <ul class="pagenav_list pagenav_items listreset">
                                      '.$this->_output.'
                                  </ul>
                              </div>';
          */
        //$this->output = $this->components['prev'].$this->_output.$this->components['next'];
        $this->output = '<div class="pagination"><div class="pagination_wrap">' . $this->_output . '</div></div>';

        $this->output = $this->totalItem > 0 && $this->totalPage > 1 ? $this->output : '';

        $this->components['pages'] = $this->output;

        return $this->components;
    }

    /**
     * Generate a Drop down menu for all page numer
     *
     * @param bool $withPageSize Navigate with page size
     * @return string Drop down menu for all page numer
     */
    public function showJumpMenu($withPageSize = false)
    {
        $option = '';
        for ($i = 1; $i <= $this->totalPage; $i++) {
            $option .= ($i == $this->currentPage) ? "<option value=\"$i\" selected>$i</option>\n" : "<option value=\"$i\">$i</option>\n";
        }
        if ($withPageSize)
            return "<select class=\"{$this->dropDownCss}\" onchange=\"window.location='{$this->baseUrl}/page/'+this[this.selectedIndex].value+'/$this->itemPerPage';return false\">$option</select>\n";
        return "<select class=\"{$this->dropDownCss}\" onchange=\"window.location='{$this->baseUrl}/page/'+this[this.selectedIndex].value;return false\">$option</select>\n";
    }

}