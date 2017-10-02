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
 * @subpackage controllers
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

class PluginsController extends Controller
{
	public function __construct()
    {
        AuthUser::load();
        if (!AuthUser::isLoggedIn())
        {
            redirect(get_url('login'));
        }
        else if (!AuthUser::hasPermission('administrator'))
        {
            Flash::set('error', __('You do not have permission to access the requested page!'));

            if( Setting::get('default_tab') === 'plugins' )
                redirect(get_url('page'));
            else
                redirect(get_url());
        }
        
        $this->setLayout('backend');
    }
	
	public function index()
	{
		if (!AuthUser::hasPermission('administrator'))
        {
            Flash::set('error', __('You do not have permission to access the requested page!'));
            redirect(get_url());
        }
		
		$this->display('setting/plugins', array(
			'plugins' => Plugin::findAll(),
			'loaded_plugins' => Plugin::$plugins
		));
	}
    
    public function activate_plugin($plugin_id)
    {
        if (!AuthUser::hasPermission('administrator'))
        {
            Flash::set('error', __('You do not have permission to access the requested page!'));
            redirect(get_url());
        }
		
		Plugin::activate($plugin_id);
		Observer::notify('plugin_after_enable', array($plugin_id));
		
		Flash::set('success', __('Plugin <b>:plugin_id</b> successfully activated!', array(':plugin_id' => Inflector::humanize($plugin_id))));
		
		redirect(get_url('plugins'));
    }
    
    public function deactivate_plugin($plugin_id)
    {
        if (!AuthUser::hasPermission('administrator'))
        {
            Flash::set('error', __('You do not have permission to access the requested page!'));
            redirect(get_url());
        }
        
        Plugin::deactivate($plugin_id);
        Observer::notify('plugin_after_disable', array($plugin_id));
		
		Flash::set('success', __('Plugin <b>:plugin_id</b> successfully deactivated!', array(':plugin_id' => Inflector::humanize($plugin_id))));
		redirect(get_url('plugins'));
    }
	
	public function js()
	{
		$out = '';
		
		foreach( Plugin::$plugins as $plugin_id => $plugin )
		{
			$file = PLUGINS_ROOT.DIRECTORY_SEPARATOR.$plugin_id.DIRECTORY_SEPARATOR.'i18n'.DIRECTORY_SEPARATOR. I18n::getLocale().'-message.js';
			
			if ( file_exists($file) )
				$out .= file_get_contents($file) . PHP_EOL . PHP_EOL;
				
			$file = PLUGINS_ROOT.DIRECTORY_SEPARATOR.$plugin_id.DIRECTORY_SEPARATOR. $plugin_id .'.js';
			
			if ( file_exists($file))
				$out .= file_get_contents($file) . PHP_EOL . PHP_EOL;
		}
		
		$file = CMS_ROOT.DIRECTORY_SEPARATOR.ADMIN_DIR_NAME.DIRECTORY_SEPARATOR.'javascripts'.DIRECTORY_SEPARATOR.'i18n'.DIRECTORY_SEPARATOR. I18n::getLocale().'-message.js';
		
		if (file_exists($file))
			$out .= file_get_contents($file) . PHP_EOL . PHP_EOL;
		
		header('Content-type: text/javascript');
		echo $out;
	}
	
	public function css()
	{
		$out = '';
		
		foreach( Plugin::$plugins as $plugin_id => $plugin )
		{
			$file = PLUGINS_ROOT.DIRECTORY_SEPARATOR.$plugin_id.DIRECTORY_SEPARATOR. $plugin_id . '.css';
			
			if ( file_exists($file) )
				$out .= file_get_contents($file) . PHP_EOL . PHP_EOL;
		}

		header('Content-type: text/css');
		echo $out;
	}
	
	
} // end class PluginsController
