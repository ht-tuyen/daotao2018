<?php
include('./solution.php');
echo "<h3><i>Test case 1 </i></h3>";
echo "<b>Input: </b> \"\" <br>";
echo "<b>Expect: </b> Should return 1 at break point 1 <br>";
echo "<b>Result: </b> ".solution("");

echo "<h3><i>Test case 2 </i></h3>";
echo "<b>Input: </b> \"(){}>\" <br>";
echo "<b>Expect: </b> Should return 0 at break point 2 <br>";
echo "<b>Result: </b> ".solution("(){}>");

echo "<h3><i>Test case 3 </i></h3>";
echo "<b>Input: </b> \"<())\" <br>";
echo "<b>Expect: </b> Should return 0 at break point 3 <br>";
echo "<b>Result: </b> ".solution("({<>}]");

echo "<h3><i>Test case 4 </i></h3>";
echo "<b>Input: </b> \"(<[]>){}{\" <br>";
echo "<b>Expect: </b> Should return 0 at break point 5 <br>";
echo "<b>Result: </b> ".solution("(<[]>){}{");

echo "<h3><i>Test case 5 </i></h3>";
echo "<b>Input: </b> \"(([]{})(())(<>))\" <br>";
echo "<b>Expect: </b> Should return 1 at break point 4 <br>";
echo "<b>Result: </b> ".solution("(([]{})(())(<>))");