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
 
/**
 * Validation Class
 *
 */
class Validator
{

// Check for email without implementation of additional email helper
// I'm afraid of its regular extension

    public static function validate_email($email)
      {
        if (is_array($email)) return false;
        $atIndex	= strrpos($email, "@");
        $domain		= substr($email, $atIndex+1);
        $local		= substr($email, 0, $atIndex);
        $domainLen	= strlen($domain);
        if ($domainLen < 1 || $domainLen > 255) {
          return false;
        }
        $allowed	= 'A-Za-z0-9!#&*+=?_-';
        $regex		= "/^[$allowed][\.$allowed]{0,63}$/";
        if ( ! preg_match($regex, $local) ) {
          return false;
        }
        $regex		= '/^[0-9\.]+$/';
        if ( preg_match($regex, $domain)) {
          return true;
        }
        $localLen	= strlen($local);
        if ($localLen < 1 || $localLen > 64) {
          return false;
        }
        $domain_array	= explode(".", rtrim( $domain, '.' ));
        $regex		= '/^[A-Za-z0-9-]{0,63}$/';
        foreach ($domain_array as $domain ) {
          if ( ! $domain ) {
            return false;
          }
          if ( ! preg_match($regex, $domain) ) {
            return false;
          }
          if ( strpos($domain, '-' ) === 0 ) {
            return false;
          }
          $length = strlen($domain) -1;
          if ( strpos($domain, '-', $length ) === $length ) {
            return false;
          }
        }
        return true;
    }
} # end of class
