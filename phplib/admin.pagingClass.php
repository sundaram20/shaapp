<?php
class pagingClass extends DbConnect 
{
	
	var $Rec_Per_Page;
	var $Curr_Page=1;
	var $Total_Rec;
	var $Num_Of_Pages;
	var $dbQuery;
	var $currentPage = 1;
	var $recordsPerPage = 10;
	
	function pagingClass($query,$Rec_Per_Page=10)
	{
		global $connNew;	
		$this->dbQuery=$query;
		$this->Rec_Per_Page=$Rec_Per_Page;
		$this->recordsPerPage = $Rec_Per_Page;
		$rs = mysqli_query($connNew, $query);
		if($rs){
			$this->Total_Rec=mysqli_num_rows($rs);
		}
		$this->Num_Of_Pages=ceil($this->Total_Rec/$this->Rec_Per_Page);
		if(empty($_REQUEST['page']))
		{
			$_REQUEST['page']='1';
		}
		$this->Curr_Page=$_REQUEST['page'];
		$this->currentPage=$_REQUEST['page'];
	}
	//maintain query string...
	function querystring($string='')
	{
	    $output = "&".$_SERVER['QUERY_STRING']."&";
	    $pairs=explode("&",$string);
	    foreach($pairs as $pair)
	    {
	        if($pair)
	        {
	            $vars=explode("=",$pair);
	            //echo $output;
	            $output=preg_replace("/&{$vars[0]}=.[^&]*&/","&",$output);
	        }
	    }
	    if(!preg_match("{^&}",$output))
	    {   
	        $output="&".$output;
	    }
	    $output="?".$string.$output;
	    return substr($output,0,-1);
	   
	}
	/*function getLinks()
	{
		$output.='<table width="40%" border="0" cellspacing="0" cellpadding="0" class="text"> <tr>';
		if($this->Num_Of_Pages>1)
		{
			$output.='<td valign="middle" nowrap="nowrap"><strong class="text">Go to page:</strong></td>';
			if($this->Curr_Page!=0)
			{
				$output.="<td valign='middle'><a href='".$this->querystring("page=0")."' class='lnk'>&nbsp;&lt;&lt;&nbsp;</a></td>";
			}
			else
			{
				$output.="<td valign='middle'><span class='lnk'>&nbsp;&lt;&lt;&nbsp;</span></td>";
			}
			//$output.="&nbsp;&nbsp;";
			if($this->Curr_Page>0)
			{
				$output.="<td valign='middle'><a href='".$this->querystring("page=".($this->Curr_Page-1))."' class='lnk'>&nbsp;&lt;&nbsp;</a></td>";
			}
			else
			{
				$output.="<td valign='middle'><span class='lnk'>&nbsp;&lt;&nbsp;</span></td>";
			}
			//$output.="&nbsp;&nbsp;";
			
			$output.='<td valign="middle">';
			
			$output.='<select name="page" onchange="submit();" class="inpt3">';
	
			for($i=0;$i<$this->Num_Of_Pages;$i++)
			{
				if($this->Curr_Page!=$i)
				{
					$output.="<option value='$i'>".($i+1)."</option>";
				}
				else
				{
					$output.="<option value='$i' selected>".($i+1)."</option>";
				}
			}
			$output.='</select>';
	
			$output.="</td>";
		
			//$output.="&nbsp;&nbsp;";
			if(($this->Num_Of_Pages-1)>$this->Curr_Page)
			{
				$output.="<td valign='middle'><a href='".$this->querystring("page=".($this->Curr_Page+1))."' class='lnk'>&nbsp;&gt;&nbsp;</a></td>";			
			}
			else
			{
				$output.="<td valign='middle'><span class='lnk'>&nbsp;&gt;&nbsp;</span></td>";			
			}
		//	$output.="&nbsp;&nbsp;";
			if(($this->Num_Of_Pages-1)!=$this->Curr_Page)
			{
				$output.="<td valign='middle'><a href='".$this->querystring("page=".($this->Num_Of_Pages-1))."' class='lnk'>&nbsp;&gt;&gt;&nbsp;</a></td>";			
			}
			else
			{
				$output.="<td valign='middle'><span class='lnk'>&nbsp;&gt;&gt;&nbsp;</span></td>";			
			}
			
		}
		$output.="</tr></table>";
		 
		return $output;
	}*/
	function getNumLinks($pageIndexes=5)
	{
		for($i=0;$i<$this->Num_Of_Pages;$i++)
		{
			if($i==($no-1)){$output.="<br />";}
			if($this->Curr_Page!=$i)
			{
				$output.="<a href='".$this->querystring("page=$i")."' class='page_link'>&nbsp;".($i+1)."</a>";
			}
			else
			{
				$output.="&nbsp;".($i+1)." ";
			}
		}
		return $output;
	}
	function profilePagging($pageIndexes =5)
	{
		if($this->Total_Rec > $this->Rec_Per_Page)
		{
			$page_pos = strpos($_SERVER['QUERY_STRING'], "&page=");
			if($page_pos === false)
				$url = substr($_SERVER['QUERY_STRING'], 0);
			else
				$url = substr($_SERVER['QUERY_STRING'], 0, $page_pos);
	     
		$data = '<table  border="0" cellspacing="0" cellpadding="1">  <tr>';
	   
			$pb = ceil($this->Curr_Page / $pageIndexes);
			$lst = $pb * $pageIndexes;
			$rp = ceil($this->Total_Rec / $this->Rec_Per_Page);
			if($this->Curr_Page > $pageIndexes)
			{
			$pre=$this->Curr_Page-1; ;
				$inn = '<td  nowrap="nowrap" valign="middle" class="pagination01">'."<a href=\"?$url&page=".$pre."\" class=\"pagination01\">&lt;&lt;Previous</a></td>";
				}
			else
				$inn = '';
			
				$data .=  $inn;
			
			$inn = "";
			for($y = ($pb - 1) * $pageIndexes; $y < $rp && $y < $lst && $rp > 1; $y++){
				if($this->Curr_Page == ($y + 1))
					$inn .= '<td align="center" valign="middle" class="pagination03">'.($y + 1).'</td>';
				else
					$inn .= '<td align="center" valign="middle" class="pagination01" >'."<a href=\"?$url&page=".($y + 1)."\" class=\"pagination01\">".($y + 1)."</a>".'</td>';
				$inn.='';
			}
				$data .= $inn;
		
			$inn="";
			$next=$this->Curr_Page+1;
			if($rp > $lst)
			{
				$inn = '<td align="center" valign="middle" class="pagination01" >'."<a href=\"?$url&page=".($next)."\" class=\"pagination01\">&gt;&gt;Next</a>".'</td>';
				}
			else
				$inn = '';
			$data .=  $inn;
      
			$data .= '</tr>
			</table>';
    		return $data;
		}
		
		
		return $data;
	}
		function profilePagging_old($pageIndexes =5)
	{
		if($this->Total_Rec > $this->Rec_Per_Page)
		{
			$page_pos = strpos($_SERVER['QUERY_STRING'], "&page=");
			if($page_pos === false)
				$url = substr($_SERVER['QUERY_STRING'], 0);
			else
				$url = substr($_SERVER['QUERY_STRING'], 0, $page_pos);
	     
		$data = '<table  border="0" cellspacing="0" cellpadding="0">  <tr>';
	   
			$pb = ceil($this->Curr_Page / $pageIndexes);
			$lst = $pb * $pageIndexes;
			$rp = ceil($this->Total_Rec / $this->Rec_Per_Page);
			if($this->Curr_Page > $pageIndexes)
				$inn = '<td  nowrap="nowrap" valign="middle" class="pagination01">'."<a href=\"?$url&page=".(($lst - (2 * $pageIndexes)) + 1)."\" class=\"pagination01\">&lt;&lt;Previous</a></td>";
			else
				$inn = '';
			
				$data .=  $inn;
			
			$inn = "";
			for($y = ($pb - 1) * $pageIndexes; $y < $rp && $y < $lst && $rp > 1; $y++){
				if($this->Curr_Page == ($y + 1))
					$inn .= '<td align="center" valign="middle" class="pagination03">'.($y + 1).'</td>';
				else
					$inn .= '<td align="center" valign="middle" class="pagination01" >'."<a href=\"?$url&page=".($y + 1)."\" class=\"pagination01\">".($y + 1)."</a>".'</td>';
				$inn.='';
			}
				$data .= $inn;
		
			$inn="";
			if($rp > $lst)
				$inn = '<td align="center" valign="middle" class="pagination01" >'."<a href=\"?$url&page=".($lst + 1)."\" class=\"pagination01\">&gt;&gt;Next</a>".'</td>';
			else
				$inn = '';
			$data .=  $inn;
      
			$data .= '</tr>
			</table>';
    		return $data;
		}
		
		
		return $data;
	}
	function getLinks_old($pageIndexes =5)
	{
		
			if($this->Total_Rec > $this->Rec_Per_Page)
		{
			$page_pos = strpos($_SERVER['QUERY_STRING'], "&page=");
			if($page_pos === false)
				$url = substr($_SERVER['QUERY_STRING'], 0);
			else
				$url = substr($_SERVER['QUERY_STRING'], 0, $page_pos);
	     
		$data = '<table  border="0" cellspacing="0" cellpadding="0">  <tr>Page:';
	   
			$pb = ceil($this->Curr_Page / $pageIndexes);
			$lst = $pb * $pageIndexes;
			$rp = ceil($this->Total_Rec / $this->Rec_Per_Page);
			if($this->Curr_Page > $pageIndexes)
				$inn = '<td  nowrap="nowrap" valign="middle" class="pagination04">'."<a href=\"?$url&page=".(($lst - (2 * $pageIndexes)) + 1)."\" class=\"pagination04\">&lt;&lt;Previous</a></td>";
			else
				$inn = '';
			
				$data .=  $inn;
			
			$inn = "";
			for($y = ($pb - 1) * $pageIndexes; $y < $rp && $y < $lst && $rp > 1; $y++){
				if($this->Curr_Page == ($y + 1))
					$inn .= '<td align="center" valign="middle" class="pagination03">'.($y + 1).'</td>';
				else
					$inn .= '<td align="center" valign="middle" class="pagination04" >'."<a href=\"?$url&page=".($y + 1)."\" class=\"pagination04\">".($y + 1)."</a>".'</td>';
				$inn.='';
			}
				$data .= $inn;
		
			$inn="";
			if($rp > $lst)
				$inn = '<td align="center" valign="middle" class="pagination04" >'."<a href=\"?$url&page=".($lst + 1)."\" class=\"pagination04\">&gt;&gt;Next</a>".'</td>';
			else
				$inn = '';
			$data .=  $inn;
      
			$data .= '</tr>
			</table>';
    		return $data;
		}
		
		
		return $data;
	}
	
	
	function getLinks($pageIndexes =5)
	{
		
			if($this->Total_Rec > $this->Rec_Per_Page)
		{
			$page_pos = strpos($_SERVER['QUERY_STRING'], "&page=");
			if($page_pos === false)
				$url = substr($_SERVER['QUERY_STRING'], 0);
			else
				$url = substr($_SERVER['QUERY_STRING'], 0, $page_pos);
	     
		$data = '<ul class="pagination pagination-sm no-margin pull-right">';
	   
			$pb = ceil($this->Curr_Page / $pageIndexes);
			$lst = $pb * $pageIndexes;
			$rp = ceil($this->Total_Rec / $this->Rec_Per_Page);
			if($this->Curr_Page > $pageIndexes)
				$inn = "<li><a href=\"?$url&page=".(($lst - (2 * $pageIndexes)) + 1)."\" >&laquo;</a></li>";
			else
				$inn = '';
			
				$data .=  $inn;
			
			$inn = "";
			for($y = ($pb - 1) * $pageIndexes; $y < $rp && $y < $lst && $rp > 1; $y++){
				if($this->Curr_Page == ($y + 1))
					$inn .= '<li class="active"><a class="current">'.($y + 1).'</a></li>';
				else
					$inn .= '<li>'."<a href=\"?$url&page=".($y + 1)."\" >".($y + 1)."</a>".'</li>';
					$inn.='';
			}
				$data .= $inn;
		
			$inn="";
			if($rp > $lst)
				$inn = '<li>'."<a href=\"?$url&page=".($lst + 1)."\" >&raquo;</a>".'</li>';
			else
				$inn = '';
			$data .=  $inn;
      
			$data .= '
			</ul>';
    		return $data;
		}
		
		
		return $data;
	}
	function getLinksImages($pageIndexes =5)
	{
		
			if($this->Total_Rec > $this->Rec_Per_Page)
		{
			$page_pos = strpos($_SERVER['QUERY_STRING'], "&page=");
			if($page_pos === false)
				$url = substr($_SERVER['QUERY_STRING'], 0);
			else
				$url = substr($_SERVER['QUERY_STRING'], 0, $page_pos);
	     
		$data = '<table  border="0" cellspacing="0" cellpadding="0">  <tr>See more photos:';
	   
			$pb = ceil($this->Curr_Page / $pageIndexes);
			$lst = $pb * $pageIndexes;
			$rp = ceil($this->Total_Rec / $this->Rec_Per_Page);
			if($this->Curr_Page > $pageIndexes)
				$inn = '<td  nowrap="nowrap" valign="middle" class="pagination04">'."<a href=\"?$url&page=".(($lst - (2 * $pageIndexes)) + 1)."\" class=\"pagination04\">&lt;&lt;Previous</a></td>";
			else
				$inn = '';
			
				$data .=  $inn;
			
			$inn = "";
			for($y = ($pb - 1) * $pageIndexes; $y < $rp && $y < $lst && $rp > 1; $y++){
				if($this->Curr_Page == ($y + 1))
					$inn .= '<td align="center" valign="middle" class="pagination03">'."<a class=\"blu-txt\" href=\"?$url&page=".($y + 1)."\" class=\"pagination04\">".($y + 1)."</a>&nbsp;&nbsp;|&nbsp;&nbsp;".'</td>';
				else
					$inn .= '<td align="center" valign="middle" class="pagination04" >'."<a class=\"blu-txt\" href=\"?$url&page=".($y + 1)."\" class=\"pagination04\">".($y + 1)."</a>&nbsp;&nbsp;|&nbsp;&nbsp;".'</td>';
				$inn.='';
			}
				$data .= $inn;
		
			$inn="";
			if($rp > $lst)
				$inn = '<td align="center" valign="middle" class="pagination04" >'."<a class=\"blu-txt\" href=\"?$url&page=".($lst + 1)."\" class=\"pagination04\">&gt;&gt;Next</a>".'</td>';
			else
				$inn = '';
			$data .=  $inn;
      
			$data .= '</tr>
			</table>';
    		return $data;
		}
		
		
		return $data;
	}
	function getNumLinks_pdf($cat_id='',$sub_cat_id='',$user_type='',$cat_name='',$subcat_name='')
	{
		if($subcat_name!='')
		{
			$filename = $subcat_name;
		}
		else if($cat_name!='')
		{
			$filename = $subcat_name;
		}
		else
		{
			$filename = 'Pdffile';
		}
		for($i=0;$i<$this->Num_Of_Pages;$i++)
		{
			if($i%10==0){$output.="<br />";}
			
				$output.="&nbsp;<a href='pdfcreate.php?page=$i&cat_id=$cat_id&sub_cat_id=$sub_cat_id&user=$user_type&filename=$filename' class='page_link' target='blank'>&nbsp; $filename".($i+1)."</a>&nbsp;";
			
		}
		return $output;
	}
	
	function getQuery()
	{
		return $this->dbQuery.=" limit ".((($this->Curr_Page-1)*$this->Rec_Per_Page)).",$this->Rec_Per_Page";
	}
	
	
	
}
// Testing of class
/*$paging=new paging("select * from tblcardmaster");
echo $paging->getLinks();
echo $paging->getQuery();
*/
?>