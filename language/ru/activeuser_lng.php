<?php
/**
*
* @package phpBB Extension - Active user
* @copyright (c) 2015 saturn-z
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
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
	'JAN'					=> 'Января',
	'FEB'					=> 'Февраля',
	'MAR'					=> 'Марта',
	'APR'					=> 'Апреля',
	'MAY'					=> 'Мая',
	'JUN'					=> 'Июня',
	'JUL'					=> 'Июля',
	'AUG'					=> 'Августа',
	'SEP'					=> 'Сентября',
	'OCT'					=> 'Октября',
	'NOV'					=> 'Ноября',
	'DEC'					=> 'Декабря',
	'JAN2'					=> 'Январь',
	'FEB2'					=> 'Февраль',
	'MAR2'					=> 'Март',
	'APR2'					=> 'Апрель',
	'MAY2'					=> 'Май',
	'JUN2'					=> 'Июнь',
	'JUL2'					=> 'Июль',
	'AUG2'					=> 'Август',
	'SEP2'					=> 'Сентябрь',
	'OCT2'					=> 'Октябрь',
	'NOV2'					=> 'Ноябрь',
	'DEC2'					=> 'Декабрь',
	'YEAR'					=> 'года',
	'FORECAST_COMMENT_NO'	=> 'Нет информации о победителях!<br />Пользователи не оставили ни одного сообщения на форуме за',
	'FORECAST_WINNERS'		=> 'Прогноз победителей на',
	'WINNERS'				=> 'Победители',
	'WINNER'				=> 'Победитель',
	'TEST_PAGE_TITLE'		=> 'Конкурс на самого активного пользователя',
	'ICON_PAGE_TITLE'		=> 'Конкурс',
	'INFO_TITLE'			=> 'Информация о конкурсе',
	'AVATAR'				=> 'Аватар',
	'NAME'					=> 'Имя',
	'DATE'					=> 'Зарегистрирован',
	'POSTS'					=> 'Сообщений',
	'POSITION'				=> 'место',
	'WAR_POSTS'				=> 'Либо Вы исключены из конкурса.',
	'MIN_POSTS'				=> '<br />Либо количество оставленных сообщений не соответствует условиям конкурса.',
	'VISIT'					=> 'Последнее посещение',
	'COMMENT'				=> 'Комментарий',
	'TOTAL_ITEMS'			=> 'Всего: <strong>%d</strong>',
	'TEXT_YOU_POSTS_TRUE'	=> 'количество оставленных Вами сообщений за',
	'TEXT_YOU_POSTS_FALSE'	=> 'Вы не оставили ни одного сообщения за',
	'TEXT_START_ACTIVEUSER'	=> 'Конкурс начался в этом месяце, победители будут отображены первого числа следующего месяца.',
));
