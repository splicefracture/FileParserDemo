<?php

include("class.FileParser.php");
include("krumo/class.krumo.php");

$parser = new FileParser("config.ini");

krumo($parser->getAll());
