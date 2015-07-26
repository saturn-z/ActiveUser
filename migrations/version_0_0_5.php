<?php
/**
*
* @package phpBB Extension - Active user
* @copyright (c) 2015 saturn-z
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace saturnZ\activeuser\migrations;

class version_0_0_5 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['activeuser_version']) && version_compare($this->config['activeuser_version'], '0.0.5', '>=');
	}

	static public function depends_on()
	{
		return array('\saturnZ\activeuser\migrations\version_0_0_4');
	}

	public function update_data()
	{
		return array(
			// Current version
			array('config.update', array('activeuser_version', '0.0.5')),

			// Add configs
			array('config.add', array('activeuser_navbar_links', '1')),
			array('config.add', array('activeuser_navigation', '1')),
			array('config.add', array('activeuser_task_last_gc', '0')),
			array('config.add', array('activeuser_task_gc', '43200')),
		);
	}

}
