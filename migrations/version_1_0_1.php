<?php
/**
*
* @package phpBB Extension - Active user
* @copyright (c) 2015 saturn-z
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace saturnZ\activeuser\migrations;

class version_1_0_1 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['activeuser_version']) && version_compare($this->config['activeuser_version'], '1.0.1', '>=');
	}

	static public function depends_on()
	{
		return array('\saturnZ\activeuser\migrations\version_0_0_6');
	}

	public function update_data()
	{
		return array(
			// Current version
			array('config.update', array('activeuser_version', '1.0.1')),
		);
	}

}
