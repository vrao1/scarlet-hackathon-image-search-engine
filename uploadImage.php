<?php
	require('PorterStemmer.php');
?>
<?php

	  function parseFile($words){
	  	$stopwords = array("a" => 1, "about" => 1, "above" => 1, "after" => 1, "again" => 1, "against" => 1, "all" => 1, "am" => 1, "an" => 1, "and" => 1, "any" => 1, "are" => 1, "aren't" => 1, "as" => 1, "at" => 1, "be" => 1, "because" => 1, "been" => 1, "before" => 1, "being" => 1, "below" => 1, "between" => 1, "both" => 1, "but" => 1, "by" => 1, "can't" => 1, "cannot" => 1, "could" => 1, "couldn't" => 1, "did" => 1, "didn't" => 1, "do" => 1, "does" => 1, "doesn't" => 1, "doing" => 1, "don't" => 1, "down" => 1, "during" => 1, "each" => 1, "few" => 1, "for" => 1, "from" => 1, "further" => 1, "had" => 1, "hadn't" => 1, "has" => 1, "hasn't" => 1, "have" => 1, "haven't" => 1, "having" => 1, "he" => 1, "he'd" => 1, "he'll" => 1, "he's" => 1, "her" => 1, "here" => 1, "here's" => 1, "hers" => 1, "herself" => 1, "him" => 1, "himself" => 1, "his" => 1, "how" => 1, "how's" => 1, "i" => 1, "i'd" => 1, "i'll" => 1, "i'm" => 1, "i've" => 1, "if" => 1, "in" => 1, "into" => 1, "is" => 1, "isn't" => 1, "it" => 1, "it's" => 1, "its" => 1, "itself" => 1, "let's" => 1, "me" => 1, "more" => 1, "most" => 1, "mustn't" => 1, "my" => 1, "myself" => 1, "no" => 1, "nor" => 1, "not" => 1, "of" => 1, "off" => 1, "on" => 1, "once" => 1, "only" => 1, "or" => 1, "other" => 1, "ought" => 1, "our" => 1, "ours" => 1, "ourselves" => 1, "out" => 1, "over" => 1, "own" => 1, "same" => 1, "shan't" => 1, "she" => 1, "she'd" => 1, "she'll" => 1, "she's" => 1, "should" => 1, "shouldn't" => 1, "so" => 1, "some" => 1, "such" => 1, "than" => 1, "that" => 1, "that's" => 1, "the" => 1, "their" => 1, "theirs" => 1, "them" => 1, "themselves" => 1, "then" => 1, "there" => 1, "there's" => 1, "these" => 1, "they" => 1, "they'd" => 1, "they'll" => 1, "they're" => 1, "they've" => 1, "this" => 1, "those" => 1, "through" => 1, "to" => 1, "too" => 1, "under" => 1, "until" => 1, "up" => 1, "very" => 1, "was" => 1, "wasn't" => 1, "we" => 1, "we'd" => 1, "we'll" => 1, "we're" => 1, "we've" => 1, "were" => 1, "weren't" => 1, "what" => 1, "what's" => 1, "when" => 1, "when's" => 1, "where" => 1, "where's" => 1, "which" => 1, "while" => 1, "who" => 1, "who's" => 1, "whom" => 1, "why" => 1, "why's" => 1, "with" => 1, "won't" => 1, "would" => 1, "wouldn't" => 1, "you" => 1, "you'd" => 1, "you'll" => 1, "you're" => 1, "you've" => 1, "your" => 1, "yours" => 1, "yourself" => 1, "yourselves" => 1);

		$key = explode("\n", $words);
		$prevImageName = "";
		$flag = false;

		foreach ($key as $entry){

			$parts = explode(" ", $entry);
			$imageName = preg_replace('/#\d+$/', '', $parts[0])  

			if($flag == false){

				$words = strtolower(array_shift($parts));
				$flag = true;	

			}else if ($prevImageName == $imageName){
				$words .= strtolower(array_shift($parts)); 
			}else{
				$words = strtolower(array_shift($parts));

			$caption = array_unique($words);
			$keywords = "";

			foreach ($caption as $w){
				if(!$stopwords[$w]){
					$word = preg_replace('/[^a-z0-9]+/', '', $w);
					$word = trim($word);

					if(strlen($word) > 0){
						$stemmedWords = PorterStemmer::Stem($word);
						$keywords = $keywords." ".$stemmedWords;
					}
				}
			}

	  				$mysql_hostname = 'localhost';
    					$mysql_username = 'root';
    					$mysql_password = 'smarthome';
    					$mysql_dbname = 'image_search';

    					$connection = mysqli_connect($mysql_hostname, $mysql_username, $mysql_password);

    					if (!$connection){
        					die("Database Connection Failed" . mysqli_error($connection));
    					}

    					$select_db = mysqli_select_db($connection, $mysql_dbname);

    					if (!$select_db){die("Database Selection Failed" . mysqli_error($connection));}


	  				$allWords = trim ($keywords ); 
	  				
					$query = "INSERT INTO SEARCH_ENGINE VALUES ('$imageName', '$keywords')"; 
					echo "$query</br>";

					$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
        				$count = mysqli_num_rows($result);
					$prevImageName = $imageName;
    		}
	  }

	  $file = 'Flickr8k.token.txt';
 	  $handle = fopen($file, 'r') or die("Unable to open file");
	  $dataset = fread($handle, filesize($file));

	  parseFile($dataset);

	  fclose($handle);

	  echo "<center><h1>Successfully Uploaded</h1></center>";

?>
<html>
<body>
</br>
</br>
<center> <font size=4><a href="index.html">BACK</a></font></center>
</body>
</html>
