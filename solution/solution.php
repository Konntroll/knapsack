<?PHP
require_once __DIR__ . '/../vendor/autoload.php';

use solution\Message;
use solution\Solver;

header('charset=utf8');

$input = file_get_contents('input.json');
$input = json_decode($input, true);

$rates = array();

foreach ($input['sms_list'] as $rate) {
    array_push($rates, new Message ($rate['price'], $rate['income']));
}

$solution = new Solver($rates, $input['required_income'], $input['max_messages']);

print_r($solution->findOptimalSequence());