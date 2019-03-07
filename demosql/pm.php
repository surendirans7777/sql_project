<?php 
// PHP program for Naive Pattern 
// Searching algorithm 

function search($pat, $txt) 
{ 
	$M = strlen($pat); 
	$N = strlen($txt); 

	// A loop to slide pat[] 
	// one by one 
	for ($i = 0; $i <= $N - $M; $i++) 
	{ 

		// For current index i, 
		// check for pattern match 
		for ($j = 0; $j < $M; $j++) 
			if ($txt[$i + $j] != $pat[$j]) 
				break; 

		// if pat[0...M-1] = 
		// txt[i, i+1, ...i+M-1] 
		if ($j == $M) 
			echo "Pattern found at index ", $i. "\n"; 
		
	} 
} 

	// Driver Code 
	$txt = "AABAACAADAABAAABAA"; 
	$pat = "AABA"; 
	search($pat, $txt); 
	
// This code is contributed by Sam007 
?> 
