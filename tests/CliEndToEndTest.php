<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class CliEndToEndTest extends TestCase
{
    private string $cliScript = __DIR__ . '/../bin/cli.php';

    /**
     * Run the CLI script with the given input lines and return the output.
     *
     * @param string[] $inputs
     * @return string
     */
    private function runCli(array $inputs): string
    {
        $process = proc_open(
            'php ' . escapeshellarg($this->cliScript),
            [
                0 => ['pipe', 'r'], // STDIN
                1 => ['pipe', 'w'], // STDOUT
                2 => ['pipe', 'w'], // STDERR
            ],
            $pipes
        );
        if (!is_resource($process)) {
            throw new \RuntimeException('Could not start CLI process');
        }
        // Write all inputs, each followed by a newline
        foreach ($inputs as $line) {
            fwrite($pipes[0], $line . "\n");
        }
        fwrite($pipes[0], "exit\n");
        fclose($pipes[0]);
        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        proc_close($process);
        return $output . $error;
    }

    public function testInsertAndReturnCoin(): void
    {
        $output = $this->runCli(['0.10, 0.10 ,RETURN-COIN']);
        $this->assertStringContainsString('0.10, 0.10', $output);
    }

    public function testBuyProductWithChange(): void
    {
        $output = $this->runCli(['1,GET-WATER']);
        $this->assertStringContainsString('WATER, 0.25, 0.10', $output);
    }

    public function testBuyProductWithNoChange(): void
    {
        $output = $this->runCli(['1, 0.25, 0.25, GET-SODA']);
        $this->assertStringContainsString('SODA', $output);
    }

    public function testServiceMode(): void
    {
        // Set all coins and products to 5
        $inputs = [
            'SERVICE',
            '5', '5', '5', '5', // for coins (assuming 4 allowed coins)
            '5', '5', '5',      // for products (assuming 3 products)
            '0.25,RETURN-COIN'
        ];
        $output = $this->runCli($inputs);
        $this->assertStringContainsString('Service mode complete.', $output);
        $this->assertStringContainsString('0.25', $output);
    }
}
