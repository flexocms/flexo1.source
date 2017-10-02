<?php if (!defined('CMS_ROOT')) die;

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
 * @subpackage controllers
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

class FrontController extends Controller
{
	public function index()
	{
		Observer::notify('frontpage_requested', array(CURRENT_URI));
		
		$page = FrontPage::find(CURRENT_URI);
		
		// if we fund it, display it!
		if ($page !== false && $page !== null)
		{
			// If page needs login, redirect to login
			if ($page->getLoginNeeded() == FrontPage::LOGIN_REQUIRED)
			{
				AuthUser::load();
				
				if (!AuthUser::isLoggedIn())
				{
					Flash::set('redirect', $page->url());
					
					redirect(BASE_URL . ADMIN_DIR_NAME . (USE_MOD_REWRITE ? '/' : '/?/') . 'login');
				}
			}
			
			Observer::notify('frontpage_found', array($page));
			
			$page->display();
		}
		else
		{
			page_not_found();
		}
	}
} // end class FrontController
