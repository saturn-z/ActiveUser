<?php
/**
*
* @package phpBB Extension - Active user
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace saturnZ\ActiveUser\acp;

class activeuser_module
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $cache, $request;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx, $phpbb_container;

		$this->db = $db;
		$this->config = $config;
		$this->config_text = $phpbb_container->get('config_text');
		$this->request = $request;
		$this->tpl_name = 'acp_activeuser';
		$this->page_title = $user->lang('ACP_ACTIVE_USER');
		add_form_key('saturnZ/ActiveUser');

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('saturnZ/ActiveUser'))
			{
				trigger_error('FORM_INVALID');
			}

			$config->set('ActiveUser_perpage', $request->variable('per_page', 6));
			$config->set('ActiveUser_warning', $request->variable('warning', 2));
			$config->set('ActiveUser_group', implode(',', $request->variable('ActiveUser_group', array(0))));
			$this->config_text->set('ActiveUser_text_title', $request->variable('ActiveUser_text_title','',true));
			$this->config_text->set('ActiveUser_text_winner', $request->variable('ActiveUser_text_winner','',true));
			$this->config_text->set('ActiveUser_text_forecast', $request->variable('ActiveUser_text_forecast','',true));

			trigger_error($user->lang['CONFIG_UPDATED'] . adm_back_link($this->u_action));
		}


		$groups_ary = explode(',', $this->config['ActiveUser_group']);
		// get group info from database and assign the block vars
		$sql = 'SELECT group_id, group_name 
				FROM ' . GROUPS_TABLE . '
				ORDER BY group_id ASC';
		$result = $this->db->sql_query($sql);
		while($row = $this->db->sql_fetchrow($result))
		{
			$template->assign_block_vars('group_setting', array(
				'SELECTED'		=> (in_array($row['group_id'], $groups_ary)) ? true : false,
				'GROUP_NAME'	=> (isset($user->lang['G_' . $row['group_name']])) ? $user->lang['G_' . $row['group_name']] : $row['group_name'],
				'GROUP_ID'		=> $row['group_id'],
			));
		}


		$template->assign_vars(array(
			'U_ACTION'			=> $this->u_action,
			'PER_PAGE'			=> (isset($this->config['ActiveUser_perpage'])) ? $this->config['ActiveUser_perpage'] : 6,
			'WARNING'			=> (isset($this->config['ActiveUser_warning'])) ? $this->config['ActiveUser_warning'] : 2,
			'TEXT'				=> $this->config_text->get('ActiveUser_text_title'),
			'TEXT_WINNER'		=> $this->config_text->get('ActiveUser_text_winner'),
			'TEXT_FORECAST'		=> $this->config_text->get('ActiveUser_text_forecast'),

		));
	}
}
