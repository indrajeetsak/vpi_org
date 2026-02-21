<?php
require 'public/index.php';
$engine = new \App\Helpers\CommitteeQueryEngine();
$result = $engine->parse('give me mobile number of president of bajli block');
print_r($result);
