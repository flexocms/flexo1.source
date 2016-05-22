<?php if(!defined('CMS_ROOT')) die;

/**
 * Flexo CMS - Content Management System. <http://flexocms.ru>
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

function tagger_url($page_slug = NULL)
{
  $page_option = ($page_slug !== NULL) ? " AND slug = {$page_slug}" : "";
	$sql = 'SELECT id FROM '.TABLE_PREFIX.'page WHERE behavior_id = "tagger"'.$page_option;
	$stmt = Record::getConnection()->prepare($sql);
	$stmt->execute();
	$id = $stmt->fetchColumn();
	if(!is_null($id) && $id !== false)
	{
		$page = Page::findbyId($id);
		$url = $page->getUri();
		return BASE_URL.$url.'/';
	}
	else
	{
		return tagger_url(NULL);
	}
}

function tags_list($tags, $option = array())
{
	$separator = array_key_exists('separator', $option) ? $option['separator'] : ', ';
	$tagger_page_slug = array_key_exists('tagger_page_slug', $option) ? $option['tagger_page_slug'] : NULL;
	$i = 1;
	foreach($tags as $tag)
	{
		$url = tagger_url($tagger_page_slug).$tag.URL_SUFFIX;
		$end = $i == count($tags) ? '.' : $separator;
		echo sprintf('<a href="%s">%s</a>%s', $url, $tag, $end);
		$i++;
	}
}

// Add behaviors
Behavior::add('tagger', 'tagger/tagger.php');
