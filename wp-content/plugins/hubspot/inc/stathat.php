<?php
function do_post_request($url, $data, $optional_headers = null)
{
        $params = array('http' => array(
                'method' => 'POST',
                'content' => $data
        ));
        if ($optional_headers !== null) {
                $params['http']['header'] = $optional_headers;
        }
        $ctx = stream_context_create($params);
        $fp = @fopen($url, 'rb', false, $ctx);
        if (!$fp) {
                throw new Exception("Problem with $url, $php_errormsg");
        }
        $response = @stream_get_contents($fp);
        if ($response === false) {
                throw new Exception("Problem reading data from $url, $php_errormsg");
        }
        return $response;
}

function do_async_post_request($url, $params)
{
        foreach ($params as $key => &$val) {
                if (is_array($val)) $val = implode(',', $val);
                $post_params[] = $key.'='.urlencode($val);
        }
        $post_string = implode('&', $post_params);
        //WPHubspotLogging::log("do_async_post_request(url=".$url.", post_string=".$post_string.")", 0);

        $parts=parse_url($url);

        $fp = fsockopen($parts['host'],
                isset($parts['port'])?$parts['port']:80,
                $errno, $errstr, 30);

        $out = "POST ".$parts['path']." HTTP/1.1\r\n";
        $out.= "Host: ".$parts['host']."\r\n";
        $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out.= "Content-Length: ".strlen($post_string)."\r\n";
        $out.= "Connection: Close\r\n\r\n";
        if (isset($post_string)) $out.= $post_string;

        fwrite($fp, $out);
        fclose($fp);
}

function stathat_count($user_key, $count)
{
        return do_async_post_request("http://api.stathat.com/c", array('key' => 'vSVGVTieLosPA6Zl', 'ukey' => $user_key, 'count' => $count));
}

function stathat_value($user_key, $value)
{
        do_async_post_request("http://api.stathat.com/v", array('key' => 'vSVGVTieLosPA6Zl', 'ukey' => $user_key, 'value' => $value));
}

function stathat_ez_count($stat_name, $count)
{
        do_async_post_request("http://api.stathat.com/ez", array('email' => 'vSVGVTieLosPA6Zl', 'stat' => $stat_name, 'count' => $count));
}

function stathat_ez_value($stat_name, $value)
{
        do_async_post_request("http://api.stathat.com/ez", array('email' => 'vSVGVTieLosPA6Zl', 'stat' => $stat_name, 'value' => $value));
}

function stathat_count_sync($user_key, $count)
{
         return do_post_request("http://api.stathat.com/c", "key=vSVGVTieLosPA6Zl&ukey=$user_key&count=$count");
}

function stathat_value_sync($user_key, $value)
{
        return do_post_request("http://api.stathat.com/v", "key=vSVGVTieLosPA6Zl&ukey=$user_key&value=$value");
}

function stathat_ez_count_sync($stat_name, $count)
{
        return do_post_request("http://api.stathat.com/ez", "email=vSVGVTieLosPA6Zl&stat=$stat_name&count=$count");
}

function stathat_ez_value_sync($stat_name, $value)
{
        return do_post_request("http://api.stathat.com/ez", "email=vSVGVTieLosPA6Zl&stat=$stat_name&value=$value");
}

?>
