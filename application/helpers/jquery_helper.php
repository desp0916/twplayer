<?PHP
//load jquery, or any other external javascript if you please
function loadJquery($url='jquery.js',$type='text/javascript') {
	echo '<script type="'.$type.'"  src="'.$url.'"></script>';
}

//load a Jquery plugin, or any other external javascript
function loadJqueryPlugin($url,$type='text/javascript'){
	echo '<script type="'.$type.'"  src="'.$url.'"></script>';
}

//allows you to write javascript, it is automatically surrounded by <script> tags, if the second argument is true it will surround the script with the $(document).ready of jQuery.
function putJavascript($script,$dReady=0){
	if($dReady==1)$script='$(document).ready(function(){'.$script.'});';
	echo '<script type=\'text/javascript\'>';
	echo $script;
	echo '</script>';
}
//writes a auto complete function in jQuery. You need to use the autocomplete.jquery.js plugin to use it. Load it with the loadJqueryPlugin() method.
//the first argument selects the input(s) that you want to have an autocomplete for
//second argument the url that needs to be called and that should return the right stuff
//the third argument has the options given as an array, see below
/*
 These are the different required options, values are defaults
inputClass => "ac_input";
resultsClass => "ac_results";
lineSeparator => "\n";
cellSeparator => "|";
minChars => 1;
delay => 400;
matchCase => 0;
matchSubset => 1;
matchContains => 0;
cacheLength => 1;
mustMatch => 0;
extraParams => {};
loadingClass => "ac_loading";
selectFirst => false;
selectOnly => false;

Optional options
onItemSelect =>'' //callback to a javascript function on item select
formatItem =>''//callback to a javascript function, how the item should be formatted
maxItemsToShow=>10 //is clear
autoFill=>true// fills the input field when you highlight an item
example:
$options=array('minChars'=>1,'matchSubset'=>1,'matchContains'=>1,'cacheLength'=>10,'onItemSelect'=>'selectItem','formatItem'=>'formatItem','selectOnly'=>1);

jQueryAutoComplete('#autocomplete','http://nmwebservices.nl/feed/home/ajaxcomplete',$options);

*/

/* TODO: remove it. deprecated.
 function jQueryAutoComplete($selector,$url,$options=false){
$ajaxOptions=false;
if($options!=false){
foreach ($options as $option=>$value){
if($ajaxOptions==false){
$ajaxOptions=$option.':'.$value;
}
else
{
$ajaxOptions.=', '.$option.':'.$value;
}
}
}
echo'<script type=\'text/javascript\'>';
echo '$().ready(function(){';
echo '$("'.$selector.'").autocomplete("'.$url.'",{'.$ajaxOptions.'})';
echo '});';
echo '</script>';
}
*/

function jq_autocomplete($selector,$url,$options=false) {
	$ajaxOptions=false;
	if($options!=false){
		foreach ($options as $option=>$value){
			if($ajaxOptions==false){
				$ajaxOptions=$option.':'.$value;
			}
			else
			{
				$ajaxOptions.=', '.$option.':'.$value;
			}
		}
	}
	echo'<script type=\'text/javascript\'>';
	echo '$().ready(function(){';
	echo '$("'.$selector.'").autocomplete("'.$url.'",{'.$ajaxOptions.'})';
	echo '});';
	echo '</script>';
}

function jq_autocomplete_local($selector,$var_name,$options=false) {
	$ajaxOptions=false;
	if($options!=false){
		foreach ($options as $option=>$value){
			if($ajaxOptions==false){
				$ajaxOptions=$option.':'.$value;
			}
			else
			{
				$ajaxOptions.=', '.$option.':'.$value;
			}
		}
	}
	echo'<script type=\'text/javascript\'>';
	echo '$().ready(function(){';
	echo '$("'.$selector.'").autocomplete('.$var_name.',{'.$ajaxOptions.'})';
	echo '});';
	echo '</script>';
}

function jq_include_autocomplete() {
	return javascript_include_tag('jquery_plugin/jquery-autocomplete/lib/jquery.ajaxQueue.js') .
	javascript_include_tag('jquery_plugin/jquery-autocomplete/jquery.autocomplete.min.js') .
	javascript_include_tag('jquery_plugin/jquery-autocomplete/lib/jquery.bgiframe.min.js') .
	stylesheet_link_tag('jquery.autocomplete.css');
}

function jq_include_helpertip() {
	return javascript_include_tag('jquery_plugin/jquery.jHelperTip.min.js');
}

function jq_include_form_validation() {
	return javascript_include_tag('jquery_plugin/jquery_validate/cmxforms.js')
	. javascript_include_tag('jquery_plugin/jquery_validate/jquery.metadata.js')
	. javascript_include_tag('jquery_plugin/jquery_validate/jquery.validate.js');
}

function jq_include_tab() {
	return javascript_include_tag('jquery-ui-tab.js')
	. stylesheet_link_tag('ui.tabs.css');
}

function jq_include_carousel() {
	return
	javascript_include_tag('jquery_plugin/jcarousel/lib/jquery.jcarousel.pack.js')
	. stylesheet_link_tag('carousel/jquery.jcarousel.css')
	. stylesheet_link_tag('carousel/ie7/skin.css')
	. stylesheet_link_tag('carousel/album/skin.css')
	. stylesheet_link_tag('carousel/photo/skin.css');
}
function jq_include_fishmenu() {
	// Note:
	// 1. 這邊不準備 css. 請前端自行準備
	// 2. 以 superfish 取代 suckerfish. 但要了解為何載入 jquery.jcarousel.css
	// return javascript_include_tag('jquery_plugin/suckerfish.js');

	return javascript_include_tag('jquery_plugin/superfish.js').stylesheet_link_tag('carousel/jquery.jcarousel.css');
}

/**
 * TODO: [kooala] - review it.
 * 產生tab工具列
 * @author bkdog [2008/08/08]
 * @param string $div_id
 * @param array $title_id_array
 * @param array $title_name_array
 * @param array $content_array
 * @return string
 */
function jq_tab($div_id, $title_id_array, $title_name_array, $content_array) {
	$js =
	sprintf('
		<script type="text/javascript">
            $(function() {
              $("#%s > ul").tabs();
            });
        </script>
	', $div_id);

	$title_html = "";
	$content = "";

	$tmp_count = count($title_id_array);

	//兩個陣列 故不使用each
	for($i=0; $i<$tmp_count; $i++ )
	{
		$title_html .= sprintf('<li><a href="#%s"><span>%s</span></a></li>', $title_id_array[$i], $title_name_array[$i]);
		$content .= sprintf('<div id="%s">%s</div>', $title_id_array[$i], $content_array[$i]);
	}

	return sprintf('%s<div id="%s"><ul>%s</ul>%s</div>', $js, $div_id, $title_html, $content);
}


/**
 * 產生兩層下拉式選單之HTML結構
 *
 * @author bkdog [2008/08/08]
 * @param string $menu_id : 選單的 ID
 * @param 2-array $menu_name_array : 第一層的顯示名稱
 * @param 2-array $menu_name_array : 第一層的連結
 * @param 2-array $li_name_array : 第二層的顯示名稱
 * @param 2-array $li_link_array : 第二層的連結
 * @return string
 */
function jq_fishmenu($menu_id , $menu_names , $menu_links,  $item_names , $item_links, $ul_class = 'nav') {

	// Note: 考量到 CSS 定義的複雜性. 由前端自行準備
	// 產生目錄結構
	$result = '';

	// counter for 第一層數量
	$menu_count = 1;

	foreach($menu_names as $menu_name)
	{
		// 第二層結構內容
		$item_content = '';

		for ($i=0; $i<count($item_names[$menu_count-1]); $i++)
		{
			$item_content .= sprintf("<li><a href=\"%s\">%s</a></li>\n",
			$item_links[$menu_count-1][$i], $item_names[$menu_count-1][$i]);
		}

		// 第一層結構內容
		$result .= sprintf("<li class=\"sf-main_item\"><a href=\"%s\">%s</a>\n<ul>%s</ul>\n</li>\n"
		, $menu_links[$menu_count-1], $menu_name, $item_content);

		$menu_count++;
	}

	return sprintf("<ul id=\"%s\" class=\"%s\">\n%s\n</ul>", $menu_id, $ul_class, $result) ;
}


function jq_fishnavibar($arr_menu ) {
	if(!is_array($arr_menu)){
		return "";
	}

	$str_li="";
	$str_ul_atrib="";
	foreach($arr_menu as $ky => $val){
		if($ky==="options"){
			foreach($val as $ky2 => $val2){
				$str_ul_atrib.=' '.$ky2.'="'.$val2.'"';
			}
		}else{
			$atrib="";
			$submenu="";
			foreach($val as $ky2 => $val2){
				if($ky2=="submenu"){
					$submenu=jq_fishnavibar($val2);
				}elseif($ky2!="link"&&$ky2!="blockname"&&$ky2!="submenu"){
					$atrib.=' '.$ky2.'="'.$val2.'"';
				}
			}
			if(isset($val['link'])){
				$link=$val['link'] ? $val['link'] : "javascript:void(0)";
			}

			$str_li.='<li'.$atrib.'>'.
						'<a href="'.(isset($link) ? $link : "javascript:void(0)").'">'.
			(isset($val['title'])? $val['title'] : '').
						'</a>'. //$submenu.
					"</li>";
		}

	}
	return '<ul'.$str_ul_atrib.'>'.$str_li.'</ul>';
}



function jq_youtube() {
	return javascript_include_tag('swfobject.js');
}

/**
 * youtube player
 *
 * @param string $div_id
 * @param string $video_id
 * @param int $width
 * @param int $height
 * @return js script
 */
function jq_youtube_player($div_id, $video_id, $width=425, $height=344) {
	$div_str = sprintf('<div id="%s">
						請安裝Flash player 8以上版本和開啟javascript以觀看影片！
						</div>', $div_id);

	$script_content = sprintf('<script type="text/javascript">
								var params = { allowScriptAccess: "always" };
								var atts = { id: "myytplayer" };
								swfobject.embedSWF("http://tw.youtube.com/v/%s&amp;border=0&amp;enablejsapi=1&amp;playerapiid=ytplayer"
								,"%s", "%s", "%s", "8", null, null, params, atts);
								 </script>', $video_id, $div_id, $width, $height);
	return $div_str.$script_content;

}

function tree_view() {
	$css = stylesheet_link_tag('jquery.treeview.css');
	$tree_view = javascript_include_tag('/jquery_plugin/tree_view/jquery.treeview.min.js');
	$cookie = javascript_include_tag('/jquery_plugin/jquery.cookie.js');
	return $css.$tree_view.$cookie;
}

function facebook_autocomplete() {
	return
	javascript_include_tag('jquery_plugin/facebook_autocomplete/jquery.autocomplete.js')
	.javascript_include_tag('jquery_plugin/facebook_autocomplete/jquery.autocompletefb.js')
	.javascript_include_tag('jquery_plugin/facebook_autocomplete/jquery.bgiframe.min.js')
	.javascript_include_tag('jquery_plugin/facebook_autocomplete/jquery.dimensions.js')
	. stylesheet_link_tag('facebook_autocomplete/jquery.autocompletefb.css');
}

function jq_include_text_count() {
	// another text count plugin
	// http://remysharp.com/demo/maxlength.html

	// http://www.swartzfager.org/blog/jQuery/plugins/textCounting/
	return javascript_include_tag('jquery_plugin/jquery.textCounting.min.js');

}

function jq_include_facebox() {
	return javascript_include_tag('jquery_plugin/facebox/facebox.js') .
	stylesheet_link_tag('facebox/facebox.css');
}

function jq_include_boxy() {
	return javascript_include_tag('jquery_plugin/boxy/src/javascripts/jquery.boxy.js') .
	stylesheet_link_tag('boxy/stylesheets/boxy.css');
}

/**
 * 1. 為 ftp/http/https/mailto 自動加上連結
 * 2. Highlight 某一字串。
 * @return string
 */
function jq_include_autolink() {
	return javascript_include_tag('jquery_plugin/jquery.autolink.js');
}

function jq_include_colorpicker() {
	return javascript_include_tag('jquery_plugin/colorpicker/js/colorpicker.js') .
			'<link rel="stylesheet" media="screen" type="text/css" href="/public/javascripts/jquery_plugin/colorpicker/css/colorpicker.css" />';
}
?>