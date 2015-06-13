<?php
/**
*
* @package phpBB Extension - Active user
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace saturnZ\activeuser\acp;

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
		add_form_key('saturnZ/activeuser');

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('saturnZ/activeuser'))
			{
				trigger_error('FORM_INVALID');
			}

			$config->set('activeuser_perpage', $request->variable('per_page', 6));
			$config->set('activeuser_warning', $request->variable('warning', 2));
			$config->set('activeuser_forecast_limit', $request->variable('forecast_limit', 3));
			$config->set('activeuser_winner_limit', $request->variable('winner_limit', 1));
			$config->set('activeuser_group', implode(',', $request->variable('activeuser_group', array(0))));
			$this->config_text->set('activeuser_text_title', $request->variable('activeuser_text_title','',true));
			$this->config_text->set('activeuser_text_winner', $request->variable('activeuser_text_winner','',true));
			$this->config_text->set('activeuser_text_forecast', $request->variable('activeuser_text_forecast','',true));
			$this->config_text->set('activeuser_excluded', implode(',', $request->variable('activeuser_excluded', array(0))));

			trigger_error($user->lang['CONFIG_UPDATED'] . adm_back_link($this->u_action));
		}

		$groups_ary = explode(',', $this->config['activeuser_group']);
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
			'PER_PAGE'			=> (isset($this->config['activeuser_perpage'])) ? $this->config['activeuser_perpage'] : 6,
			'WARNING'			=> (isset($this->config['activeuser_warning'])) ? $this->config['activeuser_warning'] : 2,
			'FORECAST_LIMIT'	=> (isset($this->config['activeuser_forecast_limit'])) ? $this->config['activeuser_forecast_limit'] : 3,
			'WINNER_LIMIT'		=> (isset($this->config['activeuser_winner_limit'])) ? $this->config['activeuser_winner_limit'] : 1,
			'TEXT'				=> $this->config_text->get('activeuser_text_title'),
			'TEXT_WINNER'		=> $this->config_text->get('activeuser_text_winner'),
			'TEXT_FORECAST'		=> $this->config_text->get('activeuser_text_forecast'),
			'EXCLUDED_FORUMS'	=> make_forum_select(explode(',', $this->config_text->get('activeuser_excluded')), false, false, true),

		));
	}
}
