<?php
/**
 * Referrers for DokuWiki
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Terence J. Grant<tjgrant@tatewake.com>
 */

function re_beginsWith( $str, $sub ) {
	return ( substr( $str, 0, strlen( $sub ) ) === $sub );
}
function re_endsWith( $str, $sub ) {
	return ( substr( $str, strlen( $str ) - strlen( $sub ) ) === $sub );
}
function re_contains($str, $sub) {
	return strstr( $str, $sub);
}

function re_log_referrers_new($refignore)
{
	global $conf;
	global $_SERVER;

	$refhi = getenv("HTTP_REFERER");
	$ref = strtolower($refhi);
	$a = 'http://'.$refignore;
	$b = 'http://www.'.$refignore;

	if (re_beginsWith($ref,$refignore) == FALSE)
	if (re_beginsWith($ref,$a) == FALSE)
	if (re_beginsWith($ref,$b) == FALSE)
	{

		#if referrer not null
		if (strcmp($refhi,"") == 0)
		{
			$refhi = "NULL";
		}
		else
		{
			$refhi = "[[".$refhi."]]";
		}

		#get current date
		$curdate = date("l");
		$curdatefile = $conf['datadir']."/wiki/referrers.txt";
		#open ref file
		if (is_writable($curdatefile))
		{
			$datas = file($curdatefile);
			$data = trim($datas[0]);

			#if we're still on the same day
			if (strcmp($data, "====== Referrers : $curdate ======") == 0)
			{
				#append
				$fp = fopen($curdatefile, "a");
			}
			else
			{
				#start over
				$fp = fopen($curdatefile, "w");
				fwrite($fp, "====== Referrers : $curdate ======\n\n");
				fwrite($fp, "//If you find the [[http://tjgrant.com/wiki/software:dokuwiki:plugin:referrers|Referrers for DokuWiki]] useful, please consider [[https://www.paypal.com/xclick/business=tjgrant%40tatewake.com&item_name=Referrers%20for%20DokuWiki%20Donation&no_shipping=1&no_note=1&tax=0&currency_code=USD&lc=US|donating]].//\n");
				fwrite($fp, "^ hostname ^ ip address ^ referrer ^\n");
			}
			#write new ref
			$rh = getenv("REMOTE_ADDR");

			fwrite($fp, "| %%".gethostbyaddr($rh)."%% | ");
			fwrite($fp, $rh);

			#make footnote if user agent is non-standard
			if (re_beginsWith(getenv(HTTP_USER_AGENT), "Mozilla") == FALSE)
			if (re_beginsWith(getenv(HTTP_USER_AGENT), "Opera") == FALSE)
				fwrite($fp, "((%%".getenv(HTTP_USER_AGENT)."%%)) ");

			fwrite($fp, "| ".$refhi." |");
			fwrite($fp, "\n");

			fclose($fp);
		}
		else if (file_exists($curdatefile))
		{
			msg("For the Referrers plugin to work, please create the page :wiki:referrers.", -1);
		}
	}
}
