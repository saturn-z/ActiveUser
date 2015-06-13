<?php
/**
*
* @package phpBB Extension - Active user
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace saturnZ\activeuser\migrations;

class version_0_0_4 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['activeuser_version']) && version_compare($this->config['activeuser_version'], '0.0.4', '>=');
	}

	static public function depends_on()
	{
		return array('\saturnZ\activeuser\migrations\version_0_0_3');
	}

	public function update_schema()
	{
		return array(
		'add_columns'		=> array(
				$this->table_prefix . 'active_user'	=> array(
					'position'			=> array('UINT:11', 1),
				),
			),
		);
	}

	public function update_data()
	{
		return array(
			// Current version
			array('config.update', array('activeuser_version', '0.0.4')),

			// Add configs
			array('config.add', array('activeuser_forecast_limit', '3')),
			array('config.add', array('activeuser_winner_limit', '1')),
		);
	}


}
