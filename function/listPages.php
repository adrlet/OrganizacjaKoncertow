<?php

  function numeracjaStron($elements, $page, $elPerPage, $reference)
  {
    $page_max = (int)(($elements == 0 ? 0 : $elements-1)/$elPerPage)+1;
    if($page > $page_max)
      $page = 1;
    $page_right = ($page_max < $page+5 ? $page_max : $page+5);
		$page_left = ($page < 6 ? 1 : $page-5);

    $html = '<ul>
     <li>Strony:</li>';

    if($page_left > 1)
      $html .= '<li><a href="'.$reference.'=1">1...</a></li>';

    for($i = $page_left; $i <= $page_right; $i++)
    {
      if($i != $page)
        $html .= '<li><a href="'.$reference.'='.$i.'">'.$i.'</a></li>';
      else
        $html .= '<li>'.$i.'</li>';
    }

    if($page_right < $page_max)
      $html .= '<li><a href="'.$reference.'='.$page_max.'">...'.$page_max.'</a></li>';

    $html .= '</ul>';

    return $html;
  }

?>