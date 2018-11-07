<?PHP
use PHPUnit\Framework\TestCase;
use solution\Solver;
use solution\Message;

class TestOptimalMessageSequencer extends TestCase
{	
	/**
	 * @dataProvider paramProvider
	 */
	public function testSolverOutput($rates, $params) 
	{
        $solution = new Solver($rates, $params['target'], $params['limit']);
        $this->assertEquals($params['expected'], $solution->findOptimalSequence());
        unset($params['expected']);
    }
    
    public function paramProvider()
    {
        return [
            'base test' => [[new Message(0.5, 0.41), //this is to test the original condition with the limit of 4 messages
                             new Message(1, 0.96),
                             new Message(2, 1.91),
                             new Message(3, 2.9)],
                            ['target' => 11,
                             'limit' => 4,
                             'expected' => [3, 3, 3, 3]]],
            'base test unlimited' => [[new Message(0.5, 0.41), //this is to test the original condition without message limit
                                       new Message(1, 0.96),
                                       new Message(2, 1.91),
                                       new Message(3, 2.9)],
                                       ['target' => 11,
                                        'limit' => PHP_INT_MAX,
                                        'expected' => [3, 3, 3, 2, 0.5]]],
            'one + one' => [[new Message(0.5, 0.41), //this is to test for the corner case where 1+1 is better than 2+0.5
                            new Message(1, 0.96),
                            new Message(2, 1.91),
                            new Message(3, 2.9)],
                            ['target' => 1.92,
                             'limit' => 4,
                             'expected' => [1, 1]]],
            'no results' => [[new Message(0.5, 0.41), //this is intended to have no results
                             new Message(1, 0.96),
                             new Message(2, 1.91),
                             new Message(3, 2.9)],
                             ['target' => 13,
                              'limit' => 4,
                              'expected' => 'Error: No results!']],
            'shorter sequence 1' => [[new Message(1, 1), //testing with a different number of message options
                                     new Message(3, 3),
                                     new Message(4, 4)],
                                     ['target' => 6,
                                      'limit' => 4,
                                      'expected' => [3, 3]]],
            'shorter sequence 2' => [[new Message(1, 1), //testing with a different number of message options
                                     new Message(3, 3),
                                     new Message(4, 4)],
                                    ['target' => 3,
                                     'limit' => 4,
                                     'expected' => [3]]],
            'big and small' => [[new Message(0.5, 0.5), //testing with vastly different rates and rate efficiencies with message limit of 4
                                new Message(10, 9)],
                                ['target' => 10,
                                 'limit' => 4,
                                 'expected' => [10, 0.5, 0.5]]],
            'big and small unlimited'  => [[new Message(0.5, 0.5), //testing with vastly different rates and rate efficiencies without message limit
                                           new Message(10, 9)],
                                           ['target' => 9,
                                            'limit' => PHP_INT_MAX,
                                            'expected' => [0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5]]],
            'longer sequence 1' => [[new Message(1, 1), //testing with a larger range of message rates
                                     new Message(2, 2),
                                     new Message(3, 3),
                                     new Message(4, 4),
                                     new Message(5, 5)],
                                    ['target' => 6,
                                     'limit' => 4,
                                     'expected' => [5, 1]]],
            'longer sequence 2' => [[new Message(1, 1), //testing with a larger range of message rates
                                     new Message(2, 2),
                                     new Message(3, 3),
                                     new Message(4, 4),
                                     new Message(5, 5)],
                                     ['target' => 19,
                                      'limit' => 4,
                                      'expected' => [5, 5, 5, 4]]],
        ];
    }
}