<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class IT_Pagination extends CI_Pagination 
{
	
	var $num_links		= 2;
	var $offset			= 0;
	
	/**
	 * Generate the pagination links
	 *
	 * @access	public
	 * @return	string
	 */
	function create_links()
	{
		// If our item count or per-page total is zero there is no need to continue.
		if ($this->total_rows == 0 || $this->per_page == 0)
			return FALSE;

		// Calculate the total number of pages
		$num_pages = ceil($this->total_rows / $this->per_page);	
		
		
		// Is there only one page? Hm... nothing more to do here then.
		if ($num_pages == 1)
			return FALSE;
		
		$paginator = array(
			'has_next'		=> FALSE,
			'has_previous'	=> FALSE,
			'total_count'   => $this->total_rows
		);

		$this->num_links = (int)$this->num_links;

		if ($this->num_links < 1)
			show_error('Your number of links must be a positive number.');
		
		if ( ! is_numeric($this->cur_page))
			$this->cur_page = 1;

		if($this->cur_page >= $num_pages){
			$this->cur_page = $num_pages;
			$paginator['has_previous'] = TRUE;
		}else{
			$paginator['has_next'] = TRUE;
		}
		
		if($this->cur_page <= 1){
			$this->cur_page = 1;
			$paginator['has_next'] = TRUE;
		}else{
			$paginator['has_previous'] = TRUE;
		}
		
		$paginator['page_num'] = $num_pages;
		$paginator['page'] = $this->cur_page;

		//記錄offset
		$this->offset = $this->per_page * ($this->cur_page - 1);

		//獲取顯示頁碼數，預設$numberOfPages = 5
		$numberOfPages = $this->num_links * 2 + 1;

		$beginPages = $endPages = array();
		for ($i = 1; $i <= $this->num_links; ++$i)
			$beginPages[] = $i;

		$endPages[0] = $num_pages;
		$beginPagecount = count($beginPages);
		for ($i = 1; $i <= $beginPagecount; ++$i)
			$endPages[$i] = $num_pages - $beginPages[$i - 1];

		if($num_pages > $numberOfPages)
		{
			switch($this->cur_page)
			{
				case in_array($this->cur_page, $beginPages):
				$beginI = 1;
				$endI = $numberOfPages;
				break;
				case in_array($this->cur_page, $endPages):
				$beginI = $num_pages - ($numberOfPages - 1);
				$endI = $num_pages;
				break;
				default:
				$middle_num = intval($numberOfPages / 2);
				$beginI = $this->cur_page - $middle_num;
				$endI = $this->cur_page + $middle_num;
				break;
			}
		}
		else
		{
			$beginI = 1;
			$endI = $num_pages;
		}
		
		for ($i = $beginI; $i <= $endI; ++$i)
		{
			$paginator['page_range'][] = $i;
		}
		
		$range_str = join(',', $paginator['page_range']);
		
		//pager begin
		if(1 == $beginI - 1)
			$range_str = '1,' . $range_str;
		if(1 < $beginI - 1)
			$range_str = '1,&hellip;,' . $range_str;

		//pager end
		if($num_pages == $endI + 1)
			$range_str .= ',' . $num_pages;
		if($num_pages > $endI + 1)
			$range_str .= ',&hellip;,' . $num_pages;
		
		$paginator['page_range'] = explode(',', $range_str);
		

		
		return $paginator;
	}
}
// END MY_Pagination Class

/* End of file AK_Pagination.php */
/* Location: ./application/libraries/MY_Pagination.php */

