<?php
/**
*
* @package phpBB Extension - Active user
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace saturnZ\activeuser\controller;

use Symfony\Component\HttpFoundation\Response;

class activeuser
{

	
	/** @var string */
	protected $config;
	protected $config_text;
	protected $request;
	protected $pagination;
	protected $db;
	protected $auth;
	protected $template;
	protected $user;
	protected $helper;
	protected $phpbb_root_path;
	protected $php_ext;
	protected $table_prefix;
	protected $phpbb_container;


	public function __construct(\phpbb\config\config $config, \phpbb\config\db_text $config_text, \phpbb\request\request_interface $request, \phpbb\pagination $pagination, \phpbb\db\driver\driver_interface $db, \phpbb\auth\auth $auth, \phpbb\template\template $template, \phpbb\user $user, \phpbb\controller\helper $helper, $phpbb_root_path, $php_ext, $table_prefix)
	{
		$this->config = $config;
		$this->config_text = $config_text;
		$this->request = $request;
		$this->pagination = $pagination;
		$this->db = $db;
		$this->auth = $auth;
		$this->template = $template;
		$this->user = $user;
		$this->helper = $helper;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->table_prefix = $table_prefix;
		define(__NAMESPACE__ . '\ACTIVE_USER_TABLE', $this->table_prefix . 'active_user');
		define(__NAMESPACE__ . '\USER_TABLE', $this->table_prefix . 'users');
		define(__NAMESPACE__ . '\POSTS_TABLE', $this->table_prefix . 'posts');

		$this->ext_root_path = 'ext/saturnZ/activeuser';
	}

	public function main()
	{

	date_default_timezone_set($this->config['board_timezone']);
	$month_Array = Array(
		"",
		$this->user->lang['JAN'],
		$this->user->lang['FEB'],
		$this->user->lang['MAR'],
		$this->user->lang['APR'],
		$this->user->lang['MAY'],
		$this->user->lang['JUN'],
		$this->user->lang['JUL'],
		$this->user->lang['AUG'],
		$this->user->lang['SEP'],
		$this->user->lang['OCT'],
		$this->user->lang['NOV'],
		$this->user->lang['DEC']
	);

	$month_real_Array = Array(
		"",
		$this->user->lang['JAN2'],
		$this->user->lang['FEB2'],
		$this->user->lang['MAR2'],
		$this->user->lang['APR2'],
		$this->user->lang['MAY2'],
		$this->user->lang['JUN2'],
		$this->user->lang['JUL2'],
		$this->user->lang['AUG2'],
		$this->user->lang['SEP2'],
		$this->user->lang['OCT2'],
		$this->user->lang['NOV2'],
		$this->user->lang['DEC2']
	);
    
$pmonth = date("n", strtotime('first day of -1 month'));
$pmonth_real = date("n");
$warning = $this->config['activeuser_warning'];
$groups = $this->config['activeuser_group'];
$perpage = $this->config['activeuser_perpage'];
$text_title = htmlspecialchars_decode($this->config_text->get('activeuser_text_title'));
$text_winner = htmlspecialchars_decode($this->config_text->get('activeuser_text_winner'));
$text_forecast = htmlspecialchars_decode($this->config_text->get('activeuser_text_forecast'));
$you_userid = $this->user->data['user_id'];
$start_activeuser = $this->config['activeuser_start'];
$excluded_forums = $this->config_text->get('activeuser_excluded');

	if ($perpage == 0)
	{
		$perpage = 1;
	}

	if ($groups == '')
	{
		$groups = 0;
	}
	if ($excluded_forums == '')
	{
		$excluded_forums = 0;
	}

				$this->template->assign_block_vars('title', array(
	'MONTH'			=> "".$this->user->lang['FORECAST_WINNERS']." $month_real_Array[$pmonth_real]",
	'WINNERS'		=> $this->user->lang['WINNERS'],
	'TEXT_TITLE'	=> $text_title,
				));

//Проверяем запись в БД, если нет добавляем
	$start_date = date("U", strtotime('first day of -1 month'));
	$start_date_array = getdate($start_date);
	$start_date_month = $start_date_array['mon'];
	$start_date_year = $start_date_array['year'];
	$start_date_timestamp = mktime(0,0,0,$start_date_month,1,$start_date_year);

if ($start_date_timestamp >= $start_activeuser)
{
$arhive_date = date("d.m.Y", strtotime('first day of -1 month'));
$sql = "SELECT date 
	FROM " . ACTIVE_USER_TABLE . " 
	WHERE date 
	LIKE '$arhive_date'";
$res = $this->db->sql_query($sql);
if ($this->db->sql_affectedrows($res) == 0)
{
	$timestamp = time();
	$curDate_ot = date("U", strtotime('first day of -1 month'));
	$curDate_do = date("U", strtotime(date('Y-m-1')));
	$date_time_array_ot = getdate($curDate_ot);
	$date_time_array_do = getdate($curDate_do);
	$month_ot = $date_time_array_ot['mon'];
	$year_ot = $date_time_array_ot['year'];
	$month_do = $date_time_array_do['mon'];
	$year_do = $date_time_array_do['year'];
	$timestamp_ot = mktime(0,0,0,$month_ot,1,$year_ot);
	$timestamp_do = mktime(0,0,0,$month_do,1,$year_do);

	$sql0 = "SELECT t.poster_id, t.forum_id, s.user_warnings, s.user_id, COUNT(poster_id) as cnt 
			FROM " . POSTS_TABLE . " AS t LEFT JOIN " . USER_TABLE . " AS s ON (s.user_id = t.poster_id) 
			WHERE post_time >= {$timestamp_ot} 
				AND post_time <= {$timestamp_do} 
					AND user_warnings <= {$warning}
						AND group_id IN ($groups)
							AND forum_id NOT IN ($excluded_forums)
			GROUP BY poster_id 
			ORDER BY cnt DESC, rand()";
	$res0 = $this->db->sql_query_limit($sql0, 1);
		if ($this->db->sql_affectedrows($res0) == 0)
		{
			$this->db->sql_query("INSERT INTO " . ACTIVE_USER_TABLE . " (user_id, date, user_posts) VALUES ('0', '$arhive_date', '0')");
		}
		else
		{
			while($row0 = $this->db->sql_fetchrow($res0))
			{
				$lider_id = $row0['poster_id'];
				$lider_posts = $row0['cnt'];
				$this->db->sql_query("INSERT INTO " . ACTIVE_USER_TABLE . " (user_id, date, user_posts) VALUES ('$lider_id', '$arhive_date', '$lider_posts')");
			}
		}
}
}
//Проверяем запись в БД, если нет добавляем

//Прогноз победителей
$timestamp = time();
$curDate_ot = date("U", strtotime(date('Y-m-1')));
$date_time_array_ot = getdate($curDate_ot);
$month_ot = $date_time_array_ot['mon'];
$year_ot = $date_time_array_ot['year'];
$timestamp_ot = mktime(0,0,1,$month_ot,1,$year_ot);
$timestamp_do = date("U");

$i = "0";

	$sql0 = "SELECT t.poster_id, t.forum_id, s.user_warnings, s.user_id, COUNT(poster_id) as cnt 
			FROM " . POSTS_TABLE . " AS t LEFT JOIN " . USER_TABLE . " AS s ON (s.user_id = t.poster_id) 
			WHERE post_time >= {$timestamp_ot} 
				AND post_time <= {$timestamp_do} 
					AND user_warnings <= {$warning} 
						AND group_id IN ($groups)
							AND forum_id NOT IN ($excluded_forums)
			GROUP BY poster_id 
			ORDER BY cnt DESC";
$res0 = $this->db->sql_query_limit($sql0, 1);
	while($row0 = $this->db->sql_fetchrow($res0))
	{
		$user_posts7 = $row0['cnt'];

		$sql = "SELECT t.poster_id, t.forum_id, s.user_warnings, s.username, s.user_avatar_type, s.user_avatar, s.user_avatar_width, s.user_avatar_height, s.user_type, s.user_colour, s.user_lastvisit, s.user_regdate, s.user_id, COUNT(poster_id) as cnt 
			FROM " . POSTS_TABLE . " AS t LEFT JOIN " . USER_TABLE . " AS s ON (s.user_id = t.poster_id) 
			WHERE post_time >= {$timestamp_ot} 
				AND post_time <= {$timestamp_do} 
					AND user_warnings <= {$warning} 
						AND group_id IN ($groups)
							AND forum_id NOT IN ($excluded_forums)
			GROUP BY poster_id 
			ORDER BY cnt DESC";
		$res = $this->db->sql_query($sql);
			while($row = $this->db->sql_fetchrow($res)) 
			{ 
				$user_posts = $row['cnt'];
				$user_lastvisit = date("d.m.Y, H:i", $row['user_lastvisit']);  
				$user_avatar = $row['user_avatar'];
				$user_avatar_type = $row['user_avatar_type'];
				$user_regdate = date("d.m.Y", $row['user_regdate']);
				$username = get_username_string((($row['user_type'] == USER_IGNORE) ? 'no_profile' : 'full'), $row['user_id'], $row['username'], $row['user_colour']);
					if ($user_avatar == "")
					{
						$user_avatar = $this->ext_root_path . '/images/no_avatar.gif';
						$user_avatar_type = AVATAR_REMOTE;
					}
				$avatar = array('user_avatar' => $user_avatar,'user_avatar_type' => $user_avatar_type,'user_avatar_width' => '40','user_avatar_height' => '40');
				$useravatar = phpbb_get_user_avatar($avatar);
					if ($user_posts==$user_posts7)
					{
						$i++;    
						$this->template->assign_block_vars('forecast', array(
			'NAME'			=> "$username",
			'POSTS'			=> "$user_posts",
			'DATE'			=> "$user_regdate",
			'AVATAR'		=> "$useravatar",
			'VISIT'			=> "$user_lastvisit",
			'COMMENT'		=> "$text_forecast",
						));
					}
			}
	}

if ($i < 1)
{
				$this->template->assign_block_vars('forecast', array(
	'NAME'			=> "",
	'POSTS'			=> "",
	'DATE'			=> "",
	'AVATAR'		=> "",
	'VISIT'			=> "",
	'COMMENT'		=> "".$this->user->lang['FORECAST_COMMENT_NO']." $month_real_Array[$pmonth_real].",
				));
}
//Прогноз победителей

//Ваши сообщения
	$sql_You = "SELECT t.poster_id, t.forum_id, s.username, s.user_id, s.user_type, s.user_colour, s.user_warnings, s.group_id, COUNT(poster_id) as cnt 
		FROM " . POSTS_TABLE . " AS t LEFT JOIN " . USER_TABLE . " AS s ON (s.user_id = t.poster_id) 
		WHERE user_id = {$you_userid} 
			AND post_time >= {$timestamp_ot} 
				AND post_time <= {$timestamp_do} 
					AND user_warnings <= {$warning} 
						AND group_id IN ($groups)
							AND forum_id NOT IN ($excluded_forums)
		GROUP BY poster_id 
		ORDER BY cnt DESC";
	$res_You = $this->db->sql_query($sql_You);
	$row_You = $this->db->sql_fetchrow($res_You);
		    $you_user_warnings = $row_You['user_warnings'];
		    $you_group_id = $row_You['group_id']; 
		    $you_user_posts = $row_You['cnt'];
		    $you_username = get_username_string((($row_You['user_type'] == USER_IGNORE) ? 'no_profile' : 'full'), $row_You['user_id'], $row_You['username'], $row_You['user_colour']);

if($you_user_posts)
{
				$this->template->assign_block_vars('you_user_posts', array(
	'NAME'			=> "$you_username",
	'POSTS'			=> "$you_user_posts",
	'MONTH'			=> "".$month_real_Array[$pmonth_real].":",
	'TEXT'			=> "".$this->user->lang['TEXT_YOU_POSTS_TRUE']."",
				));
}
else
{
	$you_username = get_username_string((($this->user->data['user_type'] == USER_IGNORE) ? 'no_profile' : 'full'), $this->user->data['user_id'], $this->user->data['username'], $this->user->data['user_colour']);
				$this->template->assign_block_vars('you_user_posts', array(
	'NAME'			=> "$you_username",
	'POSTS'			=> "".$this->user->lang['WAR_POSTS']."",
	'MONTH'			=> "".$month_real_Array[$pmonth_real].".",
	'TEXT'			=> "".$this->user->lang['TEXT_YOU_POSTS_FALSE']."",
				));
}
//Ваши сообщения 

//Список победителей по месяцам
		$start = $this->request->variable('start', 0);
		$total_count	= 0;
		$sql = 'SELECT COUNT(id) as total
			FROM ' . ACTIVE_USER_TABLE . '';
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$total_count = $row['total'];
		$this->db->sql_freeresult($result);

		$pagination_url = append_sid("{$this->phpbb_root_path}activeuser");
		$this->pagination->generate_template_pagination($pagination_url, 'pagination', 'start', $total_count, $perpage, $start);

		$sql = "SELECT t.user_id, t.date, t.user_posts, s.username, s.user_avatar_type, s.user_avatar, s.user_avatar_width, s.user_avatar_height, s.user_type, s.user_colour, s.user_lastvisit, s.user_regdate, s.user_id 
		FROM " . ACTIVE_USER_TABLE . " AS t LEFT JOIN " . USER_TABLE . " AS s ON (s.user_id = t.user_id) 
		ORDER BY t.id DESC";
		$result = $this->db->sql_query_limit($sql, $perpage, $start);

	while ($row = $this->db->sql_fetchrow($result)) 
	{
		$date_act = $row['date'];
		$posts = $row['user_posts'];
		$date_a = date("n",strtotime($date_act));
		$date_ab = $month_Array[$date_a];
		$date_abc = $month_real_Array[$date_a];
		$year = date("Y",strtotime($date_act));
		$user_lastvisit = date("d.m.Y, H:i", $row['user_lastvisit']);  
		$user_avatar = $row['user_avatar'];
		$user_avatar_type = $row['user_avatar_type'];
		$user_regdate = date("d.m.Y", $row['user_regdate']);
		$username = get_username_string((($row['user_type'] == USER_IGNORE) ? 'no_profile' : 'full'), $row['user_id'], $row['username'], $row['user_colour']);
			if ($user_avatar == "")
			{
				$user_avatar = $this->ext_root_path . '/images/no_avatar.gif';
				$user_avatar_type = AVATAR_REMOTE;
			}
		$avatar = array('user_avatar' => $user_avatar,'user_avatar_type' => $user_avatar_type,'user_avatar_width' => '40','user_avatar_height' => '40');
		$useravatar = phpbb_get_user_avatar($avatar);
			if ($posts == "0")
			{
				$this->template->assign_block_vars('arhive', array(
	'NAME'			=> "",
	'POSTS'			=> "",
	'DATE'			=> "",
	'AVATAR'		=> "",
	'VISIT'			=> "",
	'COMMENT'		=> "".$this->user->lang['FORECAST_COMMENT_NO']." $date_abc.",
				));
			}
			else
			{
				$this->template->assign_block_vars('arhive', array(
	'NAME'			=> "$username",
	'POSTS'			=> "$posts",
	'DATE'			=> "$user_regdate",
	'AVATAR'		=> "$useravatar",
	'VISIT'			=> "$user_lastvisit",
	'COMMENT'		=> "<font color=\"green\"><b>".$this->user->lang['WINNER']." $date_ab $year ".$this->user->lang['YEAR'].".</b></font><br>$text_winner",
			));
			}
	}
if ($start_date_timestamp < $start_activeuser)
{
				$this->template->assign_block_vars('start', array(
	'TEXT'			=> "".$this->user->lang['TEXT_START_ACTIVEUSER']."",
				));
}
//Список победителей по месяцам





// Output the page
		$this->template->assign_vars(array(
			'TEST_PAGE_TITLE'	=> $this->user->lang('TEST_PAGE_TITLE'),
			'TOTAL_ITEMS'		=> $this->user->lang('TOTAL_ITEMS', (int) $total_count),
			'PAGE_NUMBER'		=> $this->pagination->on_page($total_count, $perpage, $start),
		));

		page_header($this->user->lang('TEST_PAGE_TITLE'));
		$this->template->set_filenames(array(
			'body' => 'activeuser_body.html'));

		page_footer();
		return new Response($this->template->return_display('body'), 200);
	}
}
