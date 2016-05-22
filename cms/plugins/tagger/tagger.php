<?php if(!defined('CMS_ROOT')) die;

/**
 * Flexo CMS - Content Management System. <http://flexo.up.dn.ua>
 * Copyright (C) 2008 Maslakov Alexander <jmas.ukraine@gmail.com>
 * Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>
 * Copyright (C) 2008 Martijn van der Kleijn <martijn.niji@gmail.com>
 *
 * This file is part of Flexo CMS.
 *
 * Flexo CMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Flexo CMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Flexo CMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Flexo CMS has made an exception to the GNU General Public License for plugins.
 * See exception.txt for details and the full text.
 */

/**
 * @package Flexo
 * @subpackage plugins.tagger
 *
 * @author Ivan Godenov <gizomo@ya.ru>
 * @version 0.1.0
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2013
 */

/**
 * The Tagger class...
 */
class Tagger
{
    public function __construct(&$page, $params)
    {
        $this->page =& $page;
        $this->params = $params;

        switch(count($params))
        {
            case 0:
            	page_not_found();
            	break;
            case 1:
                $this->pagesByTag($params);
            break;
            default:
                page_not_found();
        }
    }
    
    public function pagesByTag($params = false)
    {
        $pdoConn = Record::getConnection();
        if(!$params) $params = $this->params;
        $pages = array();
        $tag = isset($params[0]) ? $params[0] : NULL;
        $where = " WHERE page.id = page_tag.page_id AND page_tag.tag_id = tag.id AND tag.name = '$tag'"
  	 		." AND page.status_id != ".Page::STATUS_HIDDEN." AND page.status_id != ".Page::STATUS_DRAFT." ORDER BY page.created_on DESC";
		
        // Count rows in table
        $sql_count = "SELECT count(*) FROM ".TABLE_PREFIX."page AS page, ".TABLE_PREFIX."page_tag AS page_tag, ".TABLE_PREFIX."tag AS tag" . $where;
        $query = $pdoConn->query($sql_count);
        if($query->fetchColumn() > 0)
        {
            $sql = "SELECT page.* FROM ".TABLE_PREFIX."page AS page, ".TABLE_PREFIX."page_tag AS page_tag, ".TABLE_PREFIX."tag AS tag" . $where;
            $stmt = $pdoConn->prepare($sql);
            $stmt->execute();
            while ($object = $stmt->fetchObject('FrontPage')) {
                $page = Page::findById($object->id);
                $object->url = '/'.$page->getUri();
                $pages[] = $object;
            }
            return $pages;
        }
        else return false;
    }

    public function tag($params = false)
    {
        if(!$params) $params = $this->params;

        return $params[0];
    }
} // end class Tagger
