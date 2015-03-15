<?php
/**
*
* @package phpBB Extension - Active user
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace saturnZ\ActiveUser\acp;

class activeuser_info
{
	function module()
	{
		return array(
			'filename'	=> '\saturnZ\ActiveUser\acp\activeuser_module',
			'title'		=> 'ACP_ACTIVE_USER',
			'version'	=> '0.0.2',
			'modes'		=> array(
				'settings'	=> array(
					'title' => 'ACP_ACTIVE_USER_SETTINGS', 
					'auth' => 'ext_saturnZ/ActiveUser && acl_a_board', 
					'cat' => array('ACP_ACTIVE_USER')),
			),
		);
	}
}
