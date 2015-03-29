<?php
/**
*
* @package phpBB Extension - Active user
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace saturnZ\activeuser\migrations;

class version_0_0_2 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['activeuser_version']) && version_compare($this->config['activeuser_version'], '0.0.2', '>=');
	}

	static public function depends_on()
	{
		return array('\saturnZ\activeuser\migrations\version_0_0_1');
	}

	public function update_data()
	{
		return array(
			// Current version
			array('config.update', array('activeuser_version', '0.0.2')),

			// Add configs
			array('config.add', array('activeuser_perpage', '6')),
			array('config.add', array('activeuser_warning', '2')),
			array('config.add', array('activeuser_group', '0')),
			array('config_text.add', array('activeuser_text_title', '')),
			array('config_text.add', array('activeuser_text_winner', '')),
			array('config_text.add', array('activeuser_text_forecast', '')),

			// Add ACP modules
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_ACTIVE_USER')),
			array('module.add', array('acp', 'ACP_ACTIVE_USER', array(
				'module_basename'	=> '\saturnZ\activeuser\acp\activeuser_module',
				'module_langname'	=> 'ACP_ACTIVE_USER_SETTINGS',
				'module_mode'		=> 'active_user',
				'module_auth'		=> 'ext_saturnZ/activeuser && acl_a_board', 
			))),
		);
	}


}
