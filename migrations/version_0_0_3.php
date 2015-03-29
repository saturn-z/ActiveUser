<?php
/**
*
* @package phpBB Extension - Active user
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace saturnZ\activeuser\migrations;

class version_0_0_3 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['activeuser_version']) && version_compare($this->config['activeuser_version'], '0.0.3', '>=');
	}

	static public function depends_on()
	{
		return array('\saturnZ\activeuser\migrations\version_0_0_2');
	}

	public function update_data()
	{
		date_default_timezone_set($this->config['board_timezone']);
		$activeuser_start = date("U", strtotime(date('Y-m-1')));
		$activeuser_start_array = getdate($activeuser_start);
		$activeuser_start_month = $activeuser_start_array['mon'];
		$activeuser_start_year = $activeuser_start_array['year'];
		$activeuser_start_timestamp = mktime(0,0,0,$activeuser_start_month,1,$activeuser_start_year);
		return array(
			// Current version
			array('config.update', array('activeuser_version', '0.0.3')),

			// Add configs
			array('config.add', array('activeuser_start', ''.$activeuser_start_timestamp.'')),

			array('config_text.add', array('activeuser_excluded', '')),
		);
	}


}
