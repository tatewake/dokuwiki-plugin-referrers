<?php
/**
 * Referrers for DokuWiki
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Terence J. Grant<tjgrant@tatewake.com>
 */

function re_beginsWith($str, $sub)
{
    return (substr($str, 0, strlen($sub)) === $sub);
}

function re_log_referrers_new($refignore)
{
    global $conf;
    global $_SERVER;

    $refhi = getenv("HTTP_REFERER");
    $ref = strtolower($refhi);
    $a = 'http://'.$refignore;
    $b = 'http://www.'.$refignore;

    if (re_beginsWith($ref, $refignore) == false && re_beginsWith($ref, $a) == false && re_beginsWith($ref, $b) == false) {
        if (strcmp($refhi, "") == 0) {
            $refhi = "NULL";
        } else {
            $refhi = "[[".$refhi."]]";
        }

        $curdate = date("l");
        $curdatefile = $conf['datadir']."/wiki/referrers.txt";

        if (is_writable($curdatefile)) {
            $datas = file($curdatefile);
            $data = trim($datas[0]);

            if (strcmp($data, "====== Referrers : $curdate ======") == 0) {
                $fp = fopen($curdatefile, "a");
            } else {
                $fp = fopen($curdatefile, "w");
                fwrite($fp, "====== Referrers : $curdate ======\n\n");
                fwrite($fp, "//If you find [[https://www.dokuwiki.org/plugin:referrers|Referrers for DokuWiki]] useful, please consider [[https://www.paypal.com/xclick/business=tjgrant%40tatewake.com&item_name=Referrers%20for%20DokuWiki%20Donation&no_shipping=1&no_note=1&tax=0&currency_code=USD&lc=US|donating]].//\n");
                fwrite($fp, "^ hostname ^ ip address ^ referrer ^\n");
            }

            $rh = getenv("REMOTE_ADDR");

            fwrite($fp, "| %%".gethostbyaddr($rh)."%% | ");
            fwrite($fp, $rh);

            $UA = "";

            if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $UA = $_SERVER['HTTP_USER_AGENT'];
            }

            if (re_beginsWith($UA, "Mozilla") == false &&
                re_beginsWith($UA, "Opera") == false) {
                fwrite($fp, "((%%".$UA."%%)) ");
            }

            fwrite($fp, "| ".$refhi." |");
            fwrite($fp, "\n");

            fclose($fp);
        } elseif (file_exists($curdatefile)) {
            msg("For the Referrers plugin to work, please create the page :wiki:referrers.", -1);
        }
    }
}
