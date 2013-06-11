<!DOCTYPE html>
<html>
<head>
	<title>Wordstorm</title>
	<link rel="stylesheet" type="text/css" href="view/style.css">
	<link rel="shortcut icon" type="image/x-icon" href="view/pic/favicon.ico" />
</head>
<body>

<header>
	<h1>Wordstorm</h1>

	<span class="right">all lists</span>
	<a id="lists">&nbsp;
		<ul></ul>
	</a>
	<input type="text" id="name"></input>
	<a id="add">&nbsp;</a>
	<span class="left">new list</span>

	<div>logged in as <form action="" method="post"><input type="submit" name="logout" value="log out"/></form></a><br><?php echo getUserEmail(); ?></div>
</header>

<ul id="list">
	<li></li>
	<li><span>A</span><ul></ul></li>
	<li><span>B</span><ul></ul></li>
	<li><span>C</span><ul></ul></li>
	<li><span>D</span><ul></ul></li>
	<li><span>E</span><ul></ul></li>
	<li><span>F</span><ul></ul></li>
	<li><span>G</span><ul></ul></li>
	<li><span>H</span><ul></ul></li>
	<li><span>I</span><ul></ul></li>
	<li><span>J</span><ul></ul></li>
	<li><span>K</span><ul></ul></li>
	<li><span>L</span><ul></ul></li>
	<li><span>M</span><ul></ul></li>
	<li><span>N</span><ul></ul></li>
	<li><span>O</span><ul></ul></li>
	<li><span>P</span><ul></ul></li>
	<li><span>Q</span><ul></ul></li>
	<li><span>R</span><ul></ul></li>
	<li><span>S</span><ul></ul></li>
	<li><span>T</span><ul></ul></li>
	<li><span>U</span><ul></ul></li>
	<li><span>V</span><ul></ul></li>
	<li><span>W</span><ul></ul></li>
	<li><span>X</span><ul></ul></li>
	<li><span>Y</span><ul></ul></li>
	<li><span>Z</span><ul></ul></li>
	<li><span>#</span><ul></ul></li>
</ul>

<footer>
	<span class="right">fullsreen</span>
	<a id="fullscreen" onclick="fullscreen_list();">&nbsp;</a>
	<input type="text" placeholder="word input" id="type"></input>
	<a id="saving">&nbsp;</a>
	<span class="left" id="saving_text">ready</span>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="view/scripts.js"></script>

</body>
</html>
