<?php
require 'public/index.php';
$engine = new \App\Helpers\CommitteeQueryEngine();
try {
    $result1 = $engine->parse('give me mobile number of president of bajli block');
    print_r($result1);
    
    $result2 = $engine->parse('give me mobile number of president of bajali block');
    print_r($result2);
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage();
}
