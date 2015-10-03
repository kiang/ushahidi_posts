<meta charset="utf-8" />
<pre>
<?php

$postdata = http_build_query(
        array(
            'grant_type' => 'client_credentials',
            'client_id' => 'ushahidiui',
            'client_secret' => '35e7f0bca957836d05ca0492211b0ac707671261',
            'scope' => implode(' ', [
                'posts',
                'media',
                'forms',
                'api',
                'tags',
                'savedsearches',
                'sets',
                'users',
                'stats',
                'layers',
                'config',
                'messages'
            ]),
        )
);

$opts = array('http' =>
    array(
        'method' => 'POST',
        'header' => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata
    )
);

$context = stream_context_create($opts);

$result = file_get_contents('http://ushahidi.olc.tw/api/oauth/token', false, $context);
$result = json_decode($result, true);

/*
 * Array
  (
  [access_token] => JBCXSNNz3cRHLjgmBN5zpv5dHknkEj8GvgsamLMc
  [token_type] => Bearer
  [expires] => 1443846695
  [expires_in] => 3600
  )
 */
if (!empty($result['access_token']) && !empty($result['token_type'])) {
    $opts = array('http' =>
        array(
            'method' => 'GET',
            'header' => 'Authorization: ' . $result[ 'token_type'] . ' ' . $result['access_token'],
        )
    );

    $context = stream_context_create($opts);

    $result = file_get_contents('http://ushahidi.olc.tw/api/api/v3/posts?limit=10', false,   $context);
    print_r(json_decode($result, true));
}

?>
</pre>