<?php
/**
*
* activeuser [Russian]
*
* @package Active user
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ACP_ACTIVE_USER'						=> 'Конкурс на самого активного пользователя',
	'ACP_ACTIVE_USER_EXPLAIN'				=> 'Здесь можно настроить параметры расширения',
	'ACP_ACTIVE_USER_PER_PAGE'				=> 'Количество победителей на странице',
	'ACP_ACTIVE_USER_WARNING'				=> 'Максимальное количество предупреждений',
	'ACP_ACTIVE_USER_WARNING_INFO'			=> 'Пользователи с превышающим количеством предупреждений будут исключены из конкурса.',
	'ACP_ACTIVE_USER_WINNER_LIMIT'			=> 'Количество призовых мест',
	'ACP_ACTIVE_USER_WINNER_LIMIT_INFO'		=> 'Должен быть как минимум 1 победитель!',
	'ACP_ACTIVE_USER_FORECAST_LIMIT'		=> 'Количество отображаемых претендентов на победу',
	'ACP_ACTIVE_USER_FORECAST_LIMIT_INFO'	=> 'Минимальное значение 1, лучше отображать 3-5 претендентов (можно больше).',
	'ACP_ACTIVE_USER_SAVE'					=> 'Сохранить изменения.',
	'ACP_ACTIVE_USER_SETTINGS'				=> 'Настройки',
	'ACP_ACTIVE_USER_GROUP'					=> 'Участники конкурса',
	'ACP_ACTIVE_USER_GROUP_INFO'			=> 'Выберите группы, которые будут принимать участие в конкурсе.',
	'ACP_ACTIVE_USER_CTRL'					=> 'Для множественного выбора удерживайте клавишу <strong>CTRL</strong>.',
	'ACP_ACTIVE_USER_TEXT_TITLE'			=> 'Информация о конкурсе',
	'ACP_ACTIVE_USER_TEXT_TITLE_INFO'		=> 'Текст добавляемый в блок "Информация о конкурсе".<br />Можно использовать любой <strong>html</strong> код.',
	'ACP_ACTIVE_USER_TEXT_WINNER'			=> 'Дополнительный комментарий в таблице победителей',
	'ACP_ACTIVE_USER_TEXT_WINNER_INFO'		=> 'Например: Выиграл приз 200 рублей.<br />Можно использовать любой <strong>html</strong> код.',
	'ACP_ACTIVE_USER_TEXT_FORECAST'			=> 'Комментарий в таблице прогноза победителей',
	'ACP_ACTIVE_USER_TEXT_FORECAST_INFO'	=> 'Например: Может выиграть приз, 200 рублей.<br />Можно использовать любой <strong>html</strong> код.',
	'ACP_ACTIVE_USER_EXCLUDED_FORUMS'		=> 'Исключённые форумы',
	'ACP_ACTIVE_USER_EXCLUDED_FORUMS_INFO'	=> 'Сообщения из выбранных здесь форумов не будут учитываться в конкурсе.',
));
