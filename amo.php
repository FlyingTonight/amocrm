<?php
//user id For Amocrm
const RESPONSIBLE_USER_ID = 31065362;

const MAIN_FIELD =  
[
'name'  => 'your name',    
'email' => 'memail',
'phone' => 'number',
'form_type' => 'form_id',
];
const FORM_TYPES =
[
    0 => 'basic',
    1 => 'advanced', 
    2 => 'premium', 
];

const FIELDS_IDS = 
[
    'form_type' => 1208419,
    'your_site' => 1208403,
    'mess'      => 1208411,
    'memail'    => 1208409,
    'number'    => 1208407,

    'utm_source' => 1208265,
    'utm_medium' => 1208267,
    'utm_content'=> 1208269,
    'utm_term'   => 1208337,
    'utm_campaign'=>1208339,

    'ga-id' => 1208397,
    'ym_uid'=> 1208399

];

const API_CLIEND_ID = 'b6c976eb-1539-4f3a-acc0-98cf7c240242';

const API_SECRET = ' kV5FQe37yUvXFYxQoSLRR5ThurQpgJks1Ps1HMEBSNz22F8qswHOeBKDKAngkd03';

error_reporting(E_ALL);
init_set('display_errors',true);

if(!empty($_GET['get_token']) || !empty($_GET['code']))
{
    require 'get_token.php';
    exit;
}

if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    header('Status:403');
    echo '403 - Forbidden';
    exit;
}

$_POST['ga_id'] = $_COOKIE['_gid'] ?? '';
$_POST['ym_id'] = $_COOKIE['_ym_uid'] ?? '';

if (! empty($_POST['ym_id']))
{
    $tmp = explode('.',$_POST['ga_id']);
    array_shift($tmp);
    array_shift($tmp);
    $_POST['ga_id'] = join('.',$tmp);
}
$amo = [];
foreach(MAIN_FIELDS as $field => $alias)
{
    $_POST[$field] = $_POST[$alias] ?? '';
}
    $query = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);

    $req_arr = null;
    parse_str($query, $req_arr);

    foreach (['utm_source','utm_medium','utm_content','utm_term','utm_campaign'] as $var)
    {
        $amo[$var] = $req_arr[$var] ?? '';
    }
    $amo['form_type'] = FORM_TYPES[$_POST['form_type']] ?? FORM_TYPE[0];
 
foreach(['name','ga_id','ym_id','your_site','memail','number','mess'] as $var)
{
    $amo[$var] = $_POST[$var] ?? '';
}
foreach(['name','email','phone'] as $var)
{
    $amo[$var] = $_POST[$var] ?? '';
}
$fields = [];
foreach (FIELDS_ID as $name=>$id)
{
    $fields[$id] = trim($amo[$name]);
}

setAccessToken();
$contact_id = getContact($amo['phone'],$amo['email'],$amo['name']);
$lead = createLead
("Application for site{$_SERVER['HHTP_HOST']}",
$fields,
$contact_id);

error_reporting(false);
ini_set('display_errors',false);
