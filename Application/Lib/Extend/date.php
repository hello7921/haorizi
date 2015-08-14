<?php
/**
*@author xudianyang<>
*@version $Id:calendar.php,v 1.0 2011/02/12 15:12:00 uw Exp
*@copyright &copy;2011,xudianyang
*/ 
/**
*PHP生成一个简单的在线日历
*
*@param string $language 日历语言，值为EN,表示英文，值为CN，代表中文
*@return string 返回日历的HTML代码
*/
define("EMPTY_COLUMN","");
header("content-type:text/html;charset=gb2312");
function php_calendar($language='EN'){
 $lang=array('EN','CN');
 if(!in_array($language,$lang)){
  $language="EN";
 }
 $months=array('January','February','March','April','May','June','July','August','September','October','November','December');
 $EN=array(
   'month'=>
    array('January'=>'January','February'=>'February',
      'March'=>'March','April'=>'April',
      'May'=>'May','June'=>'June',
      'July'=>'July','August'=>'August',
      'September'=>'September','October'=>'October',
      'November'=>'November','December'=>'December',),
   'week'=>
    array('Mon'=>'Mon','Tue'=>'Tue','Wed'=>'Wed','Thu'=>'Thu','Fri'=>'Fri','Sat'=>'Sat','Sun'=>'Sun'),
   'today'=>'today',
  );
 $CN=array(
   'month'=>
    array('January'=>'一月','February'=>'二月',
      'March'=>'三月','April'=>'四月',
      'May'=>'五月','June'=>'六月',
      'July'=>'七月','August'=>'八月',
      'September'=>'九月','October'=>'十月',
      'November'=>'十一月','December'=>'十二月',),
   'week'=>
    array('Mon'=>'星期一','Tue'=>'星期二','Wed'=>'星期三','Thu'=>'星期四','Fri'=>'星期五','Sat'=>'星期六','Sun'=>'星期天'),
   'today'=>'转到今天',
  );
 if(isset($_GET['month']) && in_array($_GET['month'],$months)){
  $month=$_GET['month'];
 }else{
  $month=date("F");
 }
 if(isset($_GET['year']) && is_numeric($_GET['year']) && $_GET['year']<=2038 && $_GET['year']>=1970){
  $year=$_GET['year'];
 }else{
  $year=date("Y");
 }
 $start=strtotime("$month 1st $year");
 $end=strtotime("$month ".date("t",$start)." $year");
 $previous_year=strtotime("-1 year",$start);
 $next_year=strtotime("+1 year",$start);
 $previous_month=strtotime("-1 month",$start);
 $next_month=strtotime("+1 month",$start);
 $link="<a href='?month=%s&amp;year=%s' target='_self'>%s</a>";
 if(date("Y",$previous_year)>= 1970){
  $calendar[]=sprintf($link,date("F",$start),date("Y",$previous_year),date("Y",$previous_year));
 }else{
  $calendar[]=EMPTY_COLUMN;
 }
 $calendar[]=EMPTY_COLUMN;
 $calendar[]=EMPTY_COLUMN;
 $calendar[]=date("Y",$start);
 $calendar[]=EMPTY_COLUMN;
 $calendar[]=EMPTY_COLUMN;
 if(date("Y",$next_year)<2038 && date("Y",$next_year)!=1969){
  $calendar[]=sprintf($link,date("F",$start),date("Y",$next_year),date("Y",$next_year)); 
 }else{
  $calendar[]=EMPTY_COLUMN;
 }
 $calendar[]=sprintf($link,date("F",$previous_month),date("Y",$previous_month),${$language}['month'][date("F",$previous_month)]);
 $calendar[]=EMPTY_COLUMN;
 $calendar[]=EMPTY_COLUMN;
 $calendar[]=${$language}['month'][date("F",$start)];
 $calendar[]=EMPTY_COLUMN;
 $calendar[]=EMPTY_COLUMN;
 $calendar[]=sprintf($link,date("F",$next_month),date('Y',$next_month),${$language}['month'][date('F',$next_month)]);
 $calendar[]=${$language}['week']['Mon'];
 $calendar[]=${$language}['week']['Tue'];
 $calendar[]=${$language}['week']['Wed'];
 $calendar[]=${$language}['week']['Thu'];
 $calendar[]=${$language}['week']['Fri'];
 $calendar[]=${$language}['week']['Sat'];
 $calendar[]=${$language}['week']['Sun'];
 $blank_td=date("N",$start);
 
 for($i=1;$i<$blank_td;$i++){
  $calendar[]=EMPTY_COLUMN;
 }
 $dates=range(1,date("t",$start));
 foreach($dates as $value){
  $calendar[]=$value;
 }
 $cc=count($calendar);
 for($i=1;$i<=63-$cc;$i++){
  $calendar[]=EMPTY_COLUMN;
 }
 $k=0;
 $today=date("Y")." ".date("F")." ".date("j");
 $html="<table>";
 for($i=1;$i<=9;$i++){
  $html.="<tr>";
  for($j=1;$j<=7;$j++){
   if(is_int($calendar[$k])){
    $cur_date="$year $month $calendar[$k]";
    if($cur_date == $today){
     $html.="<td width='50' style='font-weight:bold;color:blue'>".$calendar[$k++]."</td>";
    }else{
    $html.="<td width='50'>".$calendar[$k++]."</td>";
    }
   }else{
    $html.="<td width='50'>".$calendar[$k++]."</td>";
   }
  }
  $html.="</tr>";
 }
 $html.='<tr><td align="center" colspan="7">'.sprintf($link,date('F'),date('Y'),${$language}['today']).'</td></tr>';
 $html.="</table>";
 return $html;
}
echo "英文版本日历：<br />";
echo php_calendar("EN");
echo "<p />";
echo "中文版本日历：<br />";
echo php_calendar("CN");
?>