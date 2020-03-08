@section('styles')
<style>
  ul.paginationer {
    text-align:center;
    color:#829994;
    float: center;
  }
  ul.paginationer li {
    display:inline;
    padding:0 3px;
  }
  ul.paginationer a {
    color:#0d7963;
    display:inline-block;
    padding:5px 10px;
    border:1px solid #cde0dc;
    text-decoration:none;
  }
  ul.paginationer a:hover,
  ul.paginationer a.current {
    background:#0d7963;
    color:#fff;
  }
</style>
@endsection

@if(isset($data->pagination) || isset($data->data->pagination))
<?php
$current_page = isset($data->pagination->current_page) ? $data->pagination->current_page : $data->data->pagination->current_page;
$total_pages = isset($data->pagination->last_page) ? $data->pagination->last_page : $data->data->pagination->last_page;
$per_page = 10;
$page = $current_page;

$total = $total_pages;
$adjacents = "2";
$firstlabel = "&laquo; First";
$prevlabel = "&lsaquo; Prev";
$nextlabel = "Next &rsaquo;";
$lastlabel = "Last &raquo;";

$page = ($page == 0 ? 1 : $page);
$start = ($page - 1) * $per_page;

$first = 1;
$prev = $page - 1;
$next = $page + 1;

//dd($_SERVER['QUERY_STRING']);
$paramsOld = strpos($_SERVER['REQUEST_URI'],'?') != false ? $_SERVER['REQUEST_URI'] : $_SERVER['REQUEST_URI'].'?';
//dd($paramsOld);

$url = str_replace('&page='.$prev,'',$paramsOld);
$url = str_replace('?page='.$prev,'?',$url);
$url = str_replace('?page='.$page,'?',$url);
$url = str_replace('&page='.$page,'',$url);

$url .= $_SERVER['QUERY_STRING'] != "" ? strpos($_SERVER['REQUEST_URI'],'?page=') != false ? '' : '&' : '';

$lastpage = $total_pages;

$lpm1 = $lastpage - 1;

$pagination = "";
if($lastpage > 1){
    $pagination .= "<div align='center' style='float:right;'>";
    $pagination .= "<ul class='paginationer'>";
    $pagination .= "<li class='page_info'>Page {$page} of {$lastpage}</li>";

    if ($page > 1) $pagination.= "<li><a href='{$url}page={$first}'>{$firstlabel}</a></li>";
    if ($page > 1) $pagination.= "<li><a href='{$url}page={$prev}'>{$prevlabel}</a></li>";

    if ($lastpage < 7 + ($adjacents * 2)){
        for ($counter = 1; $counter <= $lastpage; $counter++){
            if ($counter == $page){
                $pagination.= "<li><a class='current'>{$counter}</a></li>";

            } else{
                $pagination.= "<li><a href='{$url}page={$counter}'>{$counter}</a></li>";
            }
        }
    } elseif($lastpage > 5 + ($adjacents * 2)){

        if($page < 1 + ($adjacents * 2)) {

            for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
                if ($counter == $page){
                    $pagination.= "<li><a class='current'>{$counter}</a></li>";
                } else{
                    $pagination.= "<li><a href='{$url}page={$counter}'>{$counter}</a></li>";
                }
            }

            $pagination.= "<li class='dot'>...</li>";
            $pagination.= "<li><a href='{$url}page={$lpm1}'>{$lpm1}</a></li>";
            $pagination.= "<li><a href='{$url}page={$lastpage}'>{$lastpage}</a></li>";

        } elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {

            $pagination.= "<li><a href='{$url}page=1'>1</a></li>";
            $pagination.= "<li><a href='{$url}page=2'>2</a></li>";
            $pagination.= "<li class='dot'>...</li>";
            for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                if ($counter == $page){
                    $pagination.= "<li><a class='current'>{$counter}</a></li>";
                } else{
                    $pagination.= "<li><a href='{$url}page={$counter}'>{$counter}</a></li>";

                }
            }
            $pagination.= "<li class='dot'>..</li>";
            $pagination.= "<li><a href='{$url}page={$lpm1}'>{$lpm1}</a></li>";
            $pagination.= "<li><a href='{$url}page={$lastpage}'>{$lastpage}</a></li>";

        } else {

            $pagination.= "<li><a href='{$url}page=1'>1</a></li>";
            $pagination.= "<li><a href='{$url}page=2'>2</a></li>";
            $pagination.= "<li class='dot'>..</li>";
            for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                if ($counter == $page){
                    $pagination.= "<li><a class='current'>{$counter}</a></li>";
                } else{
                    $pagination.= "<li><a href='{$url}page={$counter}'>{$counter}</a></li>";
                }
            }
        }
    }

    if ($page < $counter - 1) {
        $pagination.= "<li><a href='{$url}page={$next}'>{$nextlabel}</a></li>";
        $pagination.= "<li><a href='{$url}page=$lastpage'>{$lastlabel}</a></li>";
    }

    $pagination.= "</ul>";
    $pagination.= "</div>";
}


?>

<?php echo $pagination; ?>
@endif
