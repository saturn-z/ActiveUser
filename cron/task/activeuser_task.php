<?php
/**
*
* @package phpBB Extension - myportal
* @copyright (c) 2015 saturn-z
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace saturnZ\activeuser\cron\task;

class activeuser_task extends \phpbb\cron\task\base
{
protected $config;
protected $config_text;
protected $db;

   /**
* Constructor.
   *
* @param \phpbb\config\config $config The config
   */
public function __construct(\phpbb\config\config $config, \phpbb\config\db_text $config_text, \phpbb\db\driver\driver_interface $db, $table_prefix)
   {
		$this->config = $config;
		$this->config_text = $config_text;
		$this->db = $db;
		$this->table_prefix = $table_prefix;
		define(__NAMESPACE__ . '\ACTIVE_USER_TABLE', $this->table_prefix . 'active_user');
		define(__NAMESPACE__ . '\USER_TABLE', $this->table_prefix . 'users');
		define(__NAMESPACE__ . '\POSTS_TABLE', $this->table_prefix . 'posts');
   }

   /**
* Runs this cron task.
   *
* @return null
   */
public function run()
   {
		date_default_timezone_set($this->config['board_timezone']);
		$last_month = strtotime(date('d.m.Y', strtotime('first day of previous month')));
		$current_month = strtotime(date('d.m.Y', strtotime('first day of this month')));
		$warning = $this->config['activeuser_warning'];
		$groups = $this->config['activeuser_group'];
		$start_activeuser = $this->config['activeuser_start'];
		$excluded_forums = $this->config_text->get('activeuser_excluded');
		$winner_limit = $this->config['activeuser_winner_limit'];

			if ($groups == '')
			{
				$groups = 0;
			}
			if ($excluded_forums == '')
			{
				$excluded_forums = 0;
			}

		if ($last_month >= $start_activeuser)
		{
		$arhive_date = date("d.m.Y", strtotime('first day of previous month'));
		$sql = "SELECT date 
			FROM " . ACTIVE_USER_TABLE . " 
			WHERE date 
			LIKE '$arhive_date'";
		$res = $this->db->sql_query($sql);
		if ($this->db->sql_affectedrows($res) == 0)
		{

		$pos = "0";
			$sql0 = "SELECT t.poster_id, t.forum_id, s.user_warnings, s.user_id, COUNT(poster_id) as cnt 
					FROM " . POSTS_TABLE . " AS t LEFT JOIN " . USER_TABLE . " AS s ON (s.user_id = t.poster_id) 
					WHERE post_time >= {$last_month} 
						AND post_time <= {$current_month} 
							AND user_warnings <= {$warning}
								AND group_id IN ($groups)
									AND forum_id NOT IN ($excluded_forums)
					GROUP BY poster_id 
					ORDER BY cnt DESC, rand()";
			$res0 = $this->db->sql_query_limit($sql0, $winner_limit);
				if ($this->db->sql_affectedrows($res0) == 0)
				{
					$this->db->sql_query("INSERT INTO " . ACTIVE_USER_TABLE . " (user_id, date, user_posts, position) VALUES ('0', '$arhive_date', '0', '0')");
				}
				else
				{
					while($row0 = $this->db->sql_fetchrow($res0))
					{
						$pos++;
						$lider_id = $row0['poster_id'];
						$lider_posts = $row0['cnt'];
						$this->db->sql_query("INSERT INTO " . ACTIVE_USER_TABLE . " (user_id, date, user_posts, position) VALUES ('$lider_id', '$arhive_date', '$lider_posts', '$pos')");
					}
				}
		}
		}

// Do not forget to update the configuration variable for last run time.
$this->config->set('activeuser_task_last_gc', time());
   }

   /**
* Returns whether this cron task should run now, because enough time
* has passed since it was last run.
   *
* @return bool
   */
public function should_run()
   {
return $this->config['activeuser_task_last_gc'] < time() - $this->config['activeuser_task_gc'];
   }
}
