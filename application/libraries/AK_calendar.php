<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Calendar Class
 *
 * This class enables the creation of calendars
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/calendar.html
 */
class AK_Calendar extends CI_Calendar{


	function generate_week($year = '',  $week = '', $data = array())
	{
		// Set and validate the supplied month/year
		if ($year == '')
			$year  = date("Y", $this->local_time);

		if ($week == '')
			$week  = date("W", $this->local_time);

		if (strlen($year) == 1)
			$year = '200'.$year;

		if (strlen($year) == 2)
			$year = '20'.$year;

		if (strlen($week) == 1)
			$week = '0'.$week;

		$adjusted_date = $this->adjust_date($week, $year);

		$week  = $adjusted_date['week'];
		$year   = $adjusted_date['year'];


		// Set the starting day of the week
		$start_days = array('sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6);
		$start_day = ( ! isset($start_days[$this->start_day])) ? 0 : $start_days[$this->start_day];

		// Determine the total days in the month
		//////$total_days = $this->get_total_days($month, $year);
		// Set the starting day number
		//////$local_date = mktime(12, 0, 0, $month, date('j'), $year);
		//////$date = getdate($local_date);
		//////$day  = $date["mday"];

		// Set the starting day number 決定指定週數的星期一的日期
		//$local_date = week_start_date($week, $year);
		$local_date = date("Y-m-d",strtotime($year."W".$week));

		$localdate = explode("-", $local_date);
		$day = $localdate[2];

		// Determine the month    指定週數落在哪個月份
		$month = $localdate[1];

		// Determine the total days in the month 當月份的天數
		$total_days = $this->get_total_days($localdate[1], $localdate[0]);



		// Set the current month/year/day
		// We use this to determine the "today" date
		$cur_year   = date("Y", $this->local_time);
		$cur_month  = date("m", $this->local_time);
		$cur_day    = date("j", $this->local_time);

		$cur_week    = date("W", $this->local_time);


		$is_current_month = ($cur_year == $year AND $cur_month == $month) ? TRUE : FALSE;

		// Generate the template data array
		$this->parse_template();

		// Begin building the calendar output
		$out = $this->temp['table_open'];
		$out .= "\n";

		$out .= "\n";
		$out .= $this->temp['heading_row_start'];
		$out .= "\n";

		// "previous" month link
		if ($this->show_next_prev == TRUE)
		{
			// Add a trailing slash to the  URL if needed
			$this->next_prev_url = preg_replace("/(.+?)\/*$/", "\\1/",  $this->next_prev_url);

			$adjusted_date = $this->adjust_date($week - 1, $year);
			$out .= str_replace('{previous_url}', $this->next_prev_url.$adjusted_date['year'].'/'.$adjusted_date['week'], $this->temp['heading_previous_cell']);
			$out .= "\n";
		}

		// Heading containing the month/year
		$colspan = ($this->show_next_prev == TRUE) ? 6 : 7;

		$this->temp['heading_title_cell'] = str_replace('{colspan}', $colspan, $this->temp['heading_title_cell']);
		$this->temp['heading_title_cell'] = str_replace('{heading}', $this->get_month_name($month)."&nbsp;".$year, $this->temp['heading_title_cell']);

		$out .= $this->temp['heading_title_cell'];
		$out .= "\n";

		// "next" month link
		if ($this->show_next_prev == TRUE)
		{
			$adjusted_date = $this->adjust_date($week + 1, $year);
			$out .= str_replace('{next_url}', $this->next_prev_url.$adjusted_date['year'].'/'.$adjusted_date['week'], $this->temp['heading_next_cell']);
		}

		$out .= "\n";
		$out .= $this->temp['heading_row_end'];
		$out .= "\n";
		$day_names = $this->get_day_names();

		// Build the main body of the calendar
		$limit = $day + 6;

		if ($limit > $total_days)
		{
			$total_days_left = $limit - $total_days;
		}



	$isoWeekStartDate = $localdate[2];

	$isoWeekStartMonth = $localdate[1];


	





		while ($day <= $limit)
		{
			$out .= "\n";
			$out .= $this->temp['cal_row_start'];
			$out .= "\n";

					$out .= "\n";
					$out .= $this->temp['cell_blank'];
					$out .= "\n";


			for ($i = 0	; $i < 7; $i++)
			{
				if ($day > $total_days)
				{
					$day = 1;
					$limit = $total_days_left;
				}
				$out .= ($is_current_month == TRUE AND $day == $cur_day) ? $this->temp['cal_cell_start_today'] : $this->temp['cal_cell_start'];

				$the_date = mktime(0,0,0, $isoWeekStartMonth, $isoWeekStartDate+$i, $year) ;   //$month.'/'.$day;
				$the_date = date("m/d", $the_date);


				if ($day > 0 AND $day <= $limit)
				{
					if (FALSE) //(isset($data[$day]))
					{
						// Cells with content   當日有事件，將事件放入日
						$temp = ($is_current_month == TRUE AND $day == $cur_day) ? $this->temp['cal_cell_content_today'] : $this->temp['cal_cell_content'];

						if (is_array($data[$day]))
						{
							$datas_of_day = '';
							foreach ($data[$day] as $day_data) {
							   $datas_of_day .= $day_data."\n";
							}
							$out .= str_replace('{week_day}', $day_names[($start_day + $i) %7], str_replace('{day}', $the_date, str_replace('{content}', nl2br($datas_of_day), $temp)));

						} else {

							$out .= str_replace('{week_day}', $day_names[($start_day + $i) %7], str_replace('{day}', $the_date, str_replace('{content}', $data[$day], $temp)));

						}
						
					}
					else
					{
						// Cells with no content   當日沒有事件
						$temp = ($is_current_month == TRUE AND $day == $cur_day) ? $this->temp['cal_cell_no_content_today'] : $this->temp['cal_cell_no_content'];
						$out .= str_replace('{week_day}', $day_names[($start_day + $i) %7], str_replace('{day}', $the_date, $temp));
					}
				}
				else
				{
					// Blank cells
					$out .= $this->temp['cal_cell_blank'];
				}

				$out .= ($is_current_month == TRUE AND $day == $cur_day) ? $this->temp['cal_cell_end_today'] : $this->temp['cal_cell_end'];                 
				$day++;
			}
			$out .= "\n";
			$out .= $this->temp['cal_row_end'];
			$out .= "\n";
		}

	// 每個時段的事件
	$time = 10;
	$tlimit = 22;


		while ($time <= $tlimit)
		{
			
			$out .= "\n";
			$out .= $this->temp['time_row_start'];
			$out .= "\n";
			$out .= str_replace('{time}', $time.':00 ~ '.$time.':50', $this->temp['time_cell']);

			for ($i = 0; $i < 7; $i++)
			{
				$the_date = mktime(0,0,0, $isoWeekStartMonth, $isoWeekStartDate+$i, $year) ;   //$month.'/'.$day;
				$thedate = date("Ymd", $the_date);
				$cur_time = date("H");

				$out .= (FALSE) ? $this->temp['timecnt_cell_start_today'] : $this->temp['timecnt_cell_start'];
					if (isset($data[$thedate][$time]))
					{

						// Cells with content   當日有事件，將事件放入日
						$temp = ($is_current_month == TRUE AND $day == $cur_time) ? $this->temp['cal_cell_content_today'] : $this->temp['timecnt_cell_content'];

						if (is_array($data[$thedate][$time]))
						{
							$datas_of_day = '';
							foreach ($data[$thedate][$time] as $day_data) {
							   $datas_of_day .= $day_data."\n";
							}
							$out .= str_replace('{tcnt}', nl2br($datas_of_day), $temp);

						} else {

							$out .= str_replace('{tcnt}', $data[$thedate][$time], $temp);

						}
						
					}
					else
					{
						// Cells with no content   當日沒有事件
						$out .= $this->temp['timecnt_cell_blank'];
					}

				$out .= (FALSE) ? $this->temp['timecnt_cell_end'] : $this->temp['timecnt_cell_end'];

/*********************************************/

			}
				$time++;

			$out .= "\n";
			$out .= $this->temp['time_row_end'];
			$out .= "\n";
		}


		$out .= "\n";
		$out .= $this->temp['table_close'];

		return $out;
	}

	// --------------------------------------------------------------------

	/**
	 * Get Day Names
	 *
	 * Returns an array of day names (Sunday, Monday, etc.) based
	 * on the type.  Options: long, short, abrev
	 *
	 * @access	public
	 * @param	string
	 * @return	array
	 */
	function get_day_names($day_type = '', $day_name_translate = FALSE)
	{
		if ($day_type != '')
			$this->day_type = $day_type;

		if ($this->day_type == 'long')
		{
			$day_names = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
		}
		elseif ($this->day_type == 'short')
		{
			$day_names = array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');
		}
		else
		{
			$day_names = array('su', 'mo', 'tu', 'we', 'th', 'fr', 'sa');
		}

		$days = array();
		foreach ($day_names as $val)
		{
			if ($day_name_translate === TRUE
				&& $this->CI->lang->line('cal_'.$val) === FALSE)
			{
				$days[] = $this->CI->lang->line('cal_'.$val);
			}
			else
			{
				$days[] = ucfirst($val);
			}
			//$days[] = ($this->CI->lang->line('cal_'.$val) === FALSE) ? ucfirst($val) : $this->CI->lang->line('cal_'.$val);
		}

		return $days;
	}

	
	/**
	 * Parse Template
	 *
	 * Harvests the data within the template {pseudo-variables}
	 * used to display the calendar
	 *
	 * @access	public
	 * @return	void
	 */
	function parse_template()
	{
		$this->temp = $this->default_template();

		if ($this->template == '')
		{
			return;
		}

		$today = array('cal_cell_start_today', 'cal_cell_content_today', 'cal_cell_no_content_today', 'cal_cell_end_today');

		foreach (array('table_open', 'table_close', 'heading_row_start', 'heading_previous_cell', 'heading_title_cell', 'heading_next_cell', 'heading_row_end', 'week_row_start', 'week_day_cell', 'week_row_end', 'cal_row_start', 'cal_cell_start', 'cal_cell_content', 'cal_cell_no_content',  'cal_cell_blank', 'cal_cell_end', 'cal_row_end', 'cal_cell_start_today', 'cal_cell_content_today', 'cal_cell_no_content_today', 'cal_cell_end_today'
		, 'cell_blank', 'time_row_start', 'time_cell', 'time_row_end', 'timecnt_cell_start',  'timecnt_cell_end',  'timecnt_cell_content', 'timecnt_cell_blank'
		) as $val)
		{
			if (preg_match("/\{".$val."\}(.*?)\{\/".$val."\}/si", $this->template, $match))
			{
				$this->temp[$val] = $match['1'];
			}
			else
			{
				if (in_array($val, $today, TRUE))
				{
					$this->temp[$val] = $this->temp[str_replace('_today', '', $val)];
				}
			}
		}
	}

	/**
	 * Adjust Date
	 *
	 * This function makes sure that we have a valid week/year.
	 * For example, if you submit 53 as the week, the year will
	 * increment and the week will become 01.
	 *
	 * @access	public
	 * @param	integer	the week
	 * @param	integer	the year
	 * @return	array
	 */
	function adjust_date($week, $year)
	{
		$date = array();

		$date['week']	= $week;
		$date['year']	= $year;

		while ($date['week'] > 52)
		{
			$date['week'] -= 52;
			$date['year']++;
		}
		while ($date['week'] <= 0)
		{
			$date['week'] += 52;
			$date['year']--;
		}
		if (strlen($date['week']) == 1)
		{
			$date['week'] = '0'.$date['week'];
		}

		return $date;
	}

	// --------------------------------------------------------------------

}