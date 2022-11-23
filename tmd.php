<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if(!empty($_GET['imdb']) & filter_var($_GET['imdb'], FILTER_VALIDATE_URL) !== false)
	{
	$id =0;
	$url = $_GET['imdb'];
	$imdb = basename($url);
	if (preg_match('/^tt[0-9]{4,9}+$/', $imdb))
		{
		$id = filter_var($imdb, FILTER_SANITIZE_NUMBER_INT);
		}

	$file = file_get_contents("https://api.themoviedb.org/3/find/tt".$id."?api_key=%%%%%%API KEY%%%%%%%&external_source=imdb_id");
	$array = json_decode($file, TRUE);
	if(isset($array['movie_results'][0][id]))
		{
		$mid = $array['movie_results'][0][id];
		$name = format_uri($array['movie_results'][0][title]);
		$final = "https://www.themoviedb.org/movie/".$mid."-".$name;
		echo $final;
		}
	}

function format_uri( $string, $separator = '-' )
	{
    $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
    $special_cases = array( '&' => 'and', "'" => '');
    $string = mb_strtolower( trim( $string ), 'UTF-8' );
    $string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
    $string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
    $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
    $string = preg_replace("/[$separator]+/u", "$separator", $string);
    return $string;
	}
?>
