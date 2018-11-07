<?PHP
namespace solution;

class Solver
{   
	public function __construct($rates, $target, $limit = PHP_INT_MAX)
	{
        $this->rates = $rates;
		$this->target = $target;
		$this->msgsNeeded = $limit;
    }
	
	public function findOptimalSequence() 
	{
		//the following puts the message rates in descending order for further iterations
		usort($this->rates, function($a, $b) {
			return ($a->price > $b->price) ? -1 : 1;
		});
		
		$minPrice = PHP_INT_MAX; //placeholder value for subsequent comparisons
		$minElements = PHP_INT_MAX; //placeholder value for subsequent comparisons
		$result = array(); //array to output should we find a result

		for ($i = 0; $i < count($this->rates); $i++) { //iterate over each rate
			$currentPrice = 0;
			$amount = $this->target;
			$sequence = array();
			for ($k = $i; $k < count($this->rates); $k++) { //iterate over each rate starting from the one pointed at by the parent loop
				while ($amount >= $this->rates[$k]->income) {
					array_push($sequence, $this->rates[$k]->price);
					$currentPrice += $this->rates[$k]->price;
					$amount -= $this->rates[$k]->income;
					if ($currentPrice > $minPrice) {
						break 2; //if this price is higher than the previous one, no point in continuing with this sequence
					}
				}
				if ($amount > 0) { //even if the remaining amount < the current rate it may be optimal to use it anyway as other options may be even worse
					$currentMinPrice = $currentPrice + $this->rates[$k]->price;
					$currentMinElements = count($sequence) + 1;
					if (($currentMinPrice < $minPrice || //if the new price is less than the already established min price OR
			           ($currentMinPrice == $minPrice && $currentMinElements < $minElements)) && //it is equal to the min price and requires fewer messages AND
                       $currentMinElements <= $this->msgsNeeded) { //takes no more than max allowed number of messages
                        $minElements = $currentMinElements;
                        $minPrice = $currentMinPrice;
                        $result = $sequence;
                        array_push($result, $this->rates[$k]->price);
                    }
                } elseif (($currentPrice < $minPrice || //if the new price is less than the already established min price OR
                         ($currentPrice == $minPrice && count($sequence) < $minElements)) && //it is equal to the min price and requires fewer messages AND
                         count($sequence) <= $this->msgsNeeded) { //takes no more than max allowed number of messages
                    $minElements = count($sequence);
                    $minPrice = $currentPrice;
                    $result = $sequence;
                }
            }
        }

        if ($result) {
            return($result);
        } else {
            return 'Error: No results!';
        }
    }
}