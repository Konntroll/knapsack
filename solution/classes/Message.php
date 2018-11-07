<?PHP
namespace solution;

class Message 
{
    public $price, $income;
    function __construct($price, $income) 
	{
        $this->price = $price;
		$this->income = $income;
    }
}