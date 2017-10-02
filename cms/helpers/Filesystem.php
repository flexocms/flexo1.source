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
 * @subpackage helpers
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

class Filesystem extends DirectoryIterator
{	
	public function __construct($path = '.')
	{
		if (PHP_OS == 'WIN' || PHP_OS == 'WINNT')
			$path = iconv('UTF-8', 'CP1251', $path);
		
		if ( ! is_dir($path))
			throw new Exception('Directory '.$path.' not found!');
		
		parent::__construct($path);
	}
	
    public function getIterator() 
    { 
        return $this->dirArray->getIterator();
    }
	
	public function isImage()
	{
		return in_array($this->getExtension(), array('png', 'jpg', 'jpeg', 'gif'));
	}
	
	public function getImageThumb()
	{
		if ( ! $this->isImage())
			return false;
		
		return $this->getFilename();
	}
	
	public function getFilenameUTF8()
	{
		$file_name = $this->getFilename();
		
		if (PHP_OS == 'WIN' || PHP_OS == 'WINNT')
			return iconv('CP1251', 'UTF-8', $file_name);
		elseif (function_exists('mb_detect_encoding'))
			return iconv(mb_detect_encoding($file_name), 'UTF-8', $file_name );
	}
	
	public function getChmodPerms($octal = false)
	{
		return substr(sprintf('%o', $this->getPerms()), -4);
	}
	
	public function getExt()
	{
		if ( ! $this->isDir())
		{
			$pathname = $this->getPathname();
			return substr($pathname, strrpos($pathname, '.')+1);
		}
		else
			return null;
	}
} // end class Filesystem
