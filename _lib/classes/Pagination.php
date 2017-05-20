<?php
class Pagination extends SetterGetter {
    const PER_PAGE_KEY = '/Website/Pagination/Per Page';
    
    /*
     * Calculates the various attributes of the pagination 
     */
    protected function compute() {
        // sanity checks
        if (!$this->getUrl()) {
            return false;
        }
        if (!$page = $this->getPage()) {
            return false;
        }
        if (!$per_page = $this->getPerPage()) {
            return false;
        }
        if (!$total = $this->getItemsNo()) {
            return false;
        }
        
        // some inits
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
        
        // save data
        $this->setMaxPage($max_page);
        $this->setPrevPages($iPrevPages);
        $this->setNextPages($iNextPages);
        $this->setFirstPage($bFirstPage);
        $this->setLastPage($bLastPage);
        
        return true;
    }
    
	public function simple() {
		if (!$this->compute()) {
		    return '';
		}
		
		$page         = $this->getPage();
		$iPrevPages   = $this->getPrevPages();
		$iNextPages   = $this->getNextPages();
		$bFirstPage   = $this->getFirstPage();
		$bLastPage    = $this->getLastPage();
		$max_page     = $this->getMaxPage();
		$url          = $this->getUrl();
		$per_page     = $this->getPerPage();
		
		// compose html
		$sHtml = '';
		$sPrevHtml = '';
		$sNextHtml = '';
		for ($i = $page - $iPrevPages; $i<$page; $i++) {
			$sPrevHtml .= '<a href="'.$url.'&page='.$i.'">'.$i.'</a>';
		}
		for ($i = $page + 1; $i<$page+$iNextPages + 1; $i++) {
			$sNextHtml .= '<a href="'.$url.'&page='.$i.'">'.$i.'</a>';
		}
		
		$showingStart = (($page - 1) * $per_page + 1);
		$showingEnd = ($page * $per_page <= $this->getItemsNo() ? $page * $per_page : $this->getItemsNo());
		
		$sHtml .= '
                <div class="left">'
		          .__('Showing') .' '
		          .$showingStart.' - '.$showingEnd. ' '. __('of'). ' ' .$this->getItemsNo()
		          .'</div>
                <div class="right">
                '.($bFirstPage ? '<a href="'.$url.'&page=1">'.__('First page').'</a>' : '').'
                '.$sPrevHtml.'
                <span>'.$page.'</span>
                '.$sNextHtml.'
                '.($bLastPage ? '<a href="'.$url.'&page='.$max_page.'">'.__('Last page').'</a>' : '').'
                </div>
        ';

		$this->setHtml($sHtml);
		
		return true;
	}
}