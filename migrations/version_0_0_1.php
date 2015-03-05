<?php
/**
*
* @package phpBB Extension - My test
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace saturnZ\ActiveUser\migrations;

class version_0_0_1 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return;
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_schema()
	{
		return array(
			'add_tables'		=> array(
				$this->table_prefix . 'active_user'	=> array(
					'COLUMNS'		=> array(
						'id'				=> array('UINT', null, 'auto_increment'),
						'user_id'			=> array('UINT:11', 0),
						'date'				=> array('VCHAR:20', ''),
						'user_posts'			=> array('UINT:11', 0),
					),
					'PRIMARY_KEY'	=> 'id',
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_tables'		=> array(
				$this->table_prefix . 'active_user',
			),
		);
	}

	public function update_data()
	{
		return array(
			// Current version
			array('config.add', array('ActiveUser_version', '0.0.1')),
		);
	}
}
