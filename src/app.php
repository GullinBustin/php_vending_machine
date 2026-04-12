echo "Hello from the PHP Vending Machine Console App!\n";
<?php
// Dummy function for demonstration
function dummyFunction(): string {
	return "dummy result";
}

// If running directly, show output
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
	echo dummyFunction() . "\n";
}
