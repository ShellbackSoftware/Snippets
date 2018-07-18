<?php
/*
There are two separate arrays - one to build the words, one to actually link
the words to the pages. This is just for aesthetics - the $parts array is very
sanitized for what the user sees, and the $exp_path array is for the actual links.
Breadcrumbs show up on all pages in the site, sans home page and site search results.
*/

// If we have a $breadcrumbs array, go ahead and render it.
$out_string = '';
if ( isset( $breadcrumbs ) && !empty($breadcrumbs) && count( $breadcrumbs ) > 0 )
{
	$out_string = '<a href="/">Home</a> <span>/</span> ';
	$j = 1;
	foreach ( $breadcrumbs as $crumb )
	{
		if ( !empty($crumb['url']) )
		{
			$out_string .= '<a href="' . $crumb['url'] . '">' . $crumb['crumb'] . '</a>';
		} else {
			$out_string .= $crumb['crumb'];
		}

		if ( $j < count($breadcrumbs) )
		{
			$out_string .= ' <span>/</span> ';
		}
		$j++;
	}
} else {
	// If no $breadcrumbs array, try to derive breadcrumbs from REQUEST_URI

	$path = $_SERVER["REQUEST_URI"];
	$exp_path = explode('/',$path);
	// Common excess words from URL; Crude, but it works
	/* Materials, Wiki, and Group all point to dead links. These can be removed from this list if we figure out a better way to do this.*/
	$bannedWords = array("page", "pages", "show", "list", "detail", "profile", "thread", "search", "home", "materials", "wiki", "group");

	foreach ($bannedWords as &$word){
		$word = '/\b' . preg_quote($word, '/') . '\b/';
	}

	$pass1 = preg_replace($bannedWords, '', $exp_path);
	$parts = preg_replace('/[0-9]+/', '', $pass1); // Cleaning up the clutter

	// Remove "detail" in the link
	if(in_array("detail", $exp_path)){
		$target = array_search('detail',$exp_path);
		unset($exp_path[$target]);
		$final_path = array_values($exp_path);
		$exp_path = preg_replace('/[0-9]+/', '', $final_path);
	}

	// Sanity check to kill some ugliness
	$more_bad_words = array("events","members","bookmarks");
	if(!!array_intersect($more_bad_words,$parts)){
		array_pop($parts);
	}

	// Crumbs don't show up for site search, but will for library search
	$in_search=in_array("search",$exp_path);
	if(in_array("library",$exp_path)){
		array_pop($parts);
		$in_search = false;
	}

	$parts = array_map('ucfirst', $parts); // Capitalize first letter

	// Check for homepage or if search results
	if( $path != "/" && !$in_search ) {
		$out_string = '<a href="/">Home</a>';

		// Words for the crumbs
		for ($i = 1; $i < count($parts); $i++) {
			if ( !strstr($parts[$i],".") && $parts[$i] !== '' ) {
	    	$out_string .= ' <span>/</span> <a href="';
	    	// Hyperlink the words
	    	for ($j = 0; $j <= $i; $j++) {
					$out_string .= $exp_path[$j] . '/';
				}
	    	$out_string .= '">' . str_replace('-', ' ', $parts[$i]) . '</a> ';

			} else {
	   	 	$str = $parts[$i];
	    	$pos = strrpos($str,".");
	    	$parts[$i] = substr($str, 0, $pos);
	    	$out_string .= str_replace('-', ' ', $parts[$i]);
		  };
		}

		// Additional steps for library and such
		if(in_array("library", $exp_path) || in_array("events", $exp_path)){
			$out_string .= " <span>/</span> ";
			$out_string .= str_replace(">"," <span>/</span> ",$page_title);
		}
		if(in_array("members", $exp_path)){
			$out_string .= " <span>/</span> ";
			$out_string .= strstr($page_title,':',true);
		}
	}
}

if ( strlen( $out_string ) > 0 )
{
	echo '<div class="breadcrumbs-container"><span class="offscreen">You are here:</span> ' . $out_string . '</div>';
}
?>