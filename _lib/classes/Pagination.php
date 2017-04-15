<?php
class Pagination {
	function simple($url, $page, $per_page, $total) {
		if ($total == 0) {
			return '';
		}
		
		$nrPages = 2;
		
		$bFirstPage = true;
		$iPrevPages = 0;
		$iNextPages = 0;
		$bLastPage = true;
		
		// calculate the last page
		$max_page = (int) ($total / $per_page);
		if ($total % $per_page != 0) {
			$max_page++;
		}
		
		// what to show
		if ($page == 1) {
			$bFirstPage = false;
		}
		if ($page == $max_page) {
			$bLastPage = false;
		}
		if ($max_page != 1 && $max_page != 0) {
			if ($page <= $nrPages) {
				$bFirstPage = false;
				if ($page > $max_page - $nrPages) {
					$bLastPage = false;
				}
			}
			elseif ($page >= $max_page - $nrPages) {
				$bLastPage = false;
				if ($page <= $nrPages) {
					$bFirstPage = false;
				}
			}
		}
		
		if ($page > 1) {
			$iPrevPages = ($page - 1 > $nrPages ? $nrPages : $page - 1);
		}
		if ($page < $max_page) {
			$iNextPages = ($max_page - $page > $nrPages ? $nrPages : $max_page - $page);
		}
		
		// compose html
		$sHtml = '';
		$sPrevHtml = '';
		$sNextHtml = '';
		for ($i = $page - $iPrevPages; $i<$page; $i++) {
			$sPrevHtml .= '<a class="" href="'.$url.'&page='.$i.'">'.$i.'</a>';
		}
		for ($i= $page + 1; $i<$page+$iNextPages + 1; $i++) {
			$sNextHtml .= '<a class="" href="'.$url.'&page='.$i.'">'.$i.'</a>';
		}
		$sHtml .= '
            <div class="pagination">
                <div class="count">Pagina '.$page.' din '.$max_page.'</div>
                '.($bFirstPage ? '<a class="prev" href="'.$url.'&page=1">Prima Pagina</a>' : '').'
                '.$sPrevHtml.'
                <span class="current">'.$page.'</span>
                '.$sNextHtml.'
                '.($bLastPage ? '<a class="next" href="'.$url.'&page='.$max_page.'">Ultima Pagina</a>' : '').'
                </div>
                <div>
            </div>
        ';
		
		return $sHtml;
	}
}