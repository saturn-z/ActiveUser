<?php
/**
*
* @package phpBB Extension - My test
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace saturnZ\ActiveUser\controller;

use Symfony\Component\HttpFoundation\Response;

class ActiveUser
{
	protected $config;
	protected $db;
	protected $auth;
	protected $template;
	protected $user;
	protected $helper;
	protected $phpbb_root_path;
	protected $php_ext;

	public function __construct(\phpbb\config\config $config, \phpbb\request\request_interface $request, \phpbb\pagination $pagination, \phpbb\db\driver\driver_interface $db, \phpbb\auth\auth $auth, \phpbb\template\template $template, \phpbb\user $user, \phpbb\controller\helper $helper, $phpbb_root_path, $php_ext, $table_prefix)
	{
		$this->config = $config;
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

		$this->ext_root_path = 'ext/saturnZ/ActiveUser';
	}

	public function main()
	{


date_default_timezone_set('Etc/GMT-5');
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

			$this->template->assign_block_vars('title', array(
'MONTH'		=> "".$this->user->lang['FORECAST_WINNERS']." $month_real_Array[$pmonth_real]",
'WINNERS'	=> $this->user->lang['WINNERS'],
			));

//Проверяем запись в БД, если нет добавляем
$arhive_date = date("d.m.Y", strtotime('first day of -1 month'));
$sql = "SELECT date FROM " . ACTIVE_USER_TABLE . " WHERE date LIKE '$arhive_date'";
$res = mysql_query($sql);
if (mysql_num_rows($res) == 0){
date_default_timezone_set('Etc/GMT-5');
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

$sql0 = "SELECT poster_id, COUNT(poster_id) as cnt  FROM " . POSTS_TABLE . " WHERE poster_id > '2' 
AND post_time >= {$timestamp_ot} AND post_time <= {$timestamp_do}
GROUP BY poster_id ORDER BY cnt DESC, rand() limit 0,1";
$res0 = mysql_query($sql0);
if (mysql_num_rows($res0) == 0){
$this->db->sql_query("INSERT INTO " . ACTIVE_USER_TABLE . " (user_id, date, user_posts) VALUES ('0', '$arhive_date', '0')");
}
else{
while($row0 = mysql_fetch_assoc($res0))
{
$lider_id = $row0['poster_id'];
$lider_posts = $row0['cnt'];
$this->db->sql_query("INSERT INTO " . ACTIVE_USER_TABLE . " (user_id, date, user_posts) VALUES ('$lider_id', '$arhive_date', '$lider_posts')");
}
}
}
//Проверяем запись в БД, если нет добавляем

//Прогноз победителей
date_default_timezone_set('Etc/GMT-5');
$timestamp = time();
$curDate_ot = date("U", strtotime(date('Y-m-1')));
$date_time_array_ot = getdate($curDate_ot);
$month_ot = $date_time_array_ot['mon'];
$year_ot = $date_time_array_ot['year'];
$timestamp_ot = mktime(0,0,1,$month_ot,1,$year_ot);
$timestamp_do = date("U");
$i = "0";
$sql = "SELECT t.poster_id, s.username, s.user_avatar_type, s.user_avatar, s.user_avatar_width, s.user_avatar_height, s.user_type, s.user_colour, s.user_lastvisit, s.user_regdate, s.user_id, COUNT(poster_id) as cnt FROM " . POSTS_TABLE . " 
AS t LEFT JOIN " . USER_TABLE . " AS s ON (s.user_id = t.poster_id) 
WHERE poster_id > '2' AND post_time >= {$timestamp_ot} AND post_time <= {$timestamp_do} GROUP BY poster_id ORDER BY cnt DESC";
$res = mysql_query($sql);
while($row = mysql_fetch_assoc($res)) 
{ 
$user_posts = $row['cnt'];
$user_lastvisit = date("d.m.Y, H:i", $row['user_lastvisit']);  
$user_avatar = $row['user_avatar'];
$user_avatar_type = $row['user_avatar_type'];
$user_regdate = date("d.m.Y", $row['user_regdate']);
$username = get_username_string((($row['user_type'] == USER_IGNORE) ? 'no_profile' : 'full'), $row['user_id'], $row['username'], $row['user_colour']);
if ($user_avatar == ""){
$user_avatar = $this->ext_root_path . '/images/no_avatar.gif';
$user_avatar_type = AVATAR_REMOTE;
}
$avatar = array('user_avatar' => $user_avatar,'user_avatar_type' => $user_avatar_type,'user_avatar_width' => '40','user_avatar_height' => '40');
$useravatar = phpbb_get_user_avatar($avatar);
$i++;    
			$this->template->assign_block_vars('forecast', array(
'NAME'			=> "$username",
'POSTS'			=> "$user_posts",
'DATE'			=> "$user_regdate",
'AVATAR'		=> "$useravatar",
'VISIT'			=> "$user_lastvisit",
'COMMENT'		=> $this->user->lang['FORECAST_COMMENT'],
			));
}
if ($i<1){
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

//Список победителей по месяцам
$result = $this->db->sql_query("SELECT t.user_id, t.date, t.user_posts, s.username, s.user_avatar_type, s.user_avatar, s.user_avatar_width, s.user_avatar_height, s.user_type, s.user_colour, s.user_lastvisit, s.user_regdate, s.user_id FROM " . ACTIVE_USER_TABLE . " 
AS t LEFT JOIN " . USER_TABLE . " AS s ON (s.user_id = t.user_id) 
ORDER BY t.id DESC limit 0,12");
while ($row = $this->db->sql_fetchrow($result)) {
$date_act = $row['date'];
$posts = $row['user_posts'];
$date_a = date("n",strtotime($date_act));
$date_ab = $month_Array[$date_a];
$date_abc = $month_real_Array[$date_a];
$user_lastvisit = date("d.m.Y, H:i", $row['user_lastvisit']);  
$user_avatar = $row['user_avatar'];
$user_avatar_type = $row['user_avatar_type'];
$user_regdate = date("d.m.Y", $row['user_regdate']);
$username = get_username_string((($row['user_type'] == USER_IGNORE) ? 'no_profile' : 'full'), $row['user_id'], $row['username'], $row['user_colour']);
if ($user_avatar == ""){
$user_avatar = $this->ext_root_path . '/images/no_avatar.gif';
$user_avatar_type = AVATAR_REMOTE;
}
$avatar = array('user_avatar' => $user_avatar,'user_avatar_type' => $user_avatar_type,'user_avatar_width' => '40','user_avatar_height' => '40');
$useravatar = phpbb_get_user_avatar($avatar);
if ($posts == "0"){
			$this->template->assign_block_vars('arhive', array(
'NAME'			=> "",
'POSTS'			=> "",
'DATE'			=> "",
'AVATAR'		=> "",
'VISIT'			=> "",
'COMMENT'		=> "".$this->user->lang['FORECAST_COMMENT_NO']." $date_abc.",
			));
}
else{
			$this->template->assign_block_vars('arhive', array(
'NAME'			=> "$username",
'POSTS'			=> "$posts",
'DATE'			=> "$user_regdate",
'AVATAR'		=> "$useravatar",
'VISIT'			=> "$user_lastvisit",
'COMMENT'		=> "<font color=\"green\"><b>".$this->user->lang['WINNER']." $date_ab.</b></font>".$this->user->lang['WINNER_COMMENT']."",
			));
}}
//Список победителей по месяцам





// Output the page
		$this->template->assign_vars(array(
			'TEST_PAGE_TITLE'	=> $this->user->lang('TEST_PAGE_TITLE'),
		));

		page_header($this->user->lang('TEST_PAGE_TITLE'));
		$this->template->set_filenames(array(
			'body' => 'ActiveUser_body.html'));

		page_footer();
		return new Response($this->template->return_display('body'), 200);
	}
}
