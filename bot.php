<?php

require_once 'Faker/src/autoload.php';

$cookie_jar = tempnam('/tmp','cookie');
$cookies = Array();

$c = curl_init();
curl_setopt($c, CURLOPT_HEADERFUNCTION, "curlResponseHeaderCallback");
curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36");
curl_setopt($c, CURLOPT_URL, 'https://www.pokemon.com/us/pokemon-trainer-club/sign-up/');
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($c, CURLOPT_HEADER, 1);
curl_setopt($c, CURLOPT_COOKIEFILE, $cookie_jar);
curl_setopt($c, CURLOPT_COOKIEJAR, $cookie_jar);
$page = curl_exec($c);

//file_put_contents("bot.html", $page);
//die();

preg_match('/csrftoken=([^;]+)/', $page, $matches);

$fields = array(
	"csrfmiddlewaretoken" => $matches[1],
	"dob" => "1938-12-21",
	"country" => "US",
	"country" => "US"
);

$fields_string = "";
foreach($fields as $key=>$value) { $fields_string .= $key.'='.urlencode($value).'&'; }
rtrim($fields_string, '&');

curl_setopt($c, CURLOPT_HEADERFUNCTION, "curlResponseHeaderCallback");
curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36");
curl_setopt($c, CURLOPT_URL, 'https://club.pokemon.com/us/pokemon-trainer-club/sign-up/');
curl_setopt($c, CURLOPT_REFERER, 'https://club.pokemon.com/us/pokemon-trainer-club/sign-up/');
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($c, CURLOPT_HEADER, 1);
curl_setopt($c, CURLOPT_POST, count($fields));
curl_setopt($c, CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($c, CURLOPT_COOKIEFILE, $cookie_jar);
curl_setopt($c, CURLOPT_COOKIEJAR, $cookie_jar);
$page = curl_exec($c);

$faker = Faker\Factory::create();

$usrname = $faker->firstname . rand(1, 99999);
$password = $faker->firstname . rand(1, 99999);
$email = $faker->email;

$fields = array(
	"csrfmiddlewaretoken" => $matches[1],
	"username" => $usrname,
	"password" => $password,
	"confirm_password" => $password,
	"email" => $email,
	"confirm_email" => $email,
	"public_profile_opt_in" => "True",
	"screen_name" => $usrname,
	"terms" => "on"
);

$fields_string = "";
foreach($fields as $key=>$value) { $fields_string .= $key.'='.urlencode($value).'&'; }
rtrim($fields_string, '&');

curl_setopt($c, CURLOPT_URL, 'https://club.pokemon.com/us/pokemon-trainer-club/parents/sign-up');
curl_setopt($c, CURLOPT_REFERER, 'https://club.pokemon.com/us/pokemon-trainer-club/parents/sign-up');
curl_setopt($c, CURLOPT_POST, count($fields));
curl_setopt($c, CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($c, CURLOPT_COOKIEFILE, $cookie_jar);
curl_setopt($c, CURLOPT_COOKIEJAR, $cookie_jar);
$page = curl_exec($c);

curl_close($c);
unlink($cookie_jar) or die("Can't unlink $cookie_jar");


echo "Username: " . $usrname . "\n";
echo "Password: " . $password . "\n";
echo "Email: " . $email . "\n";


function curlResponseHeaderCallback($ch, $headerLine) {
    global $cookies;
    if (preg_match('/^Set-Cookie:\s*([^;]*)/mi', $headerLine, $cookie) == 1)
        $cookies[] = $cookie;
    return strlen($headerLine); // Needed by curl
}
