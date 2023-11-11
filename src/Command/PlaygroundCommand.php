<?php

declare(strict_types=1);

namespace App\Command;

use ArtisanSdk\SRP\Config;
use ArtisanSdk\SRP\Server;
use Symfony\Component\Console\Command\Command;
use Windwalker\Console\CommandInterface;
use Windwalker\Console\CommandWrapper;
use Windwalker\Console\IOInterface;

#[CommandWrapper(
    description: ''
)]
class PlaygroundCommand implements CommandInterface
{
    /**
     * configure
     *
     * @param  Command  $command
     *
     * @return  void
     */
    public function configure(Command $command): void
    {
        //
    }

    /**
     * Executes the current command.
     *
     * @param  IOInterface  $io
     *
     * @return  int Return 0 is success, 1-255 is failure.
     */
    public function execute(IOInterface $io): int
    {
        $srpSample = new Server(
            Config::fromArray(
                [
                    // The large safe prime N as a decimal string
                    "prime" => "21766174458617435773191008891802753781907668374255538511144643224689886235383840957210909013086056401571399717235807266581649606472148410291413364152197364477180887395655483738115072677402235101762521901569820740293149529620419333266262073471054548368736039519702486226506248861060256971802984953561121442680157668000761429988222457090413873973970171927093992114751765168063614761119615476233422096442783117971236371647333871414335895773474667308967050807005509320424799678417036867928316761272274230314067548291133582479583061439577559347101961771406173684378522703483495337037655006751328447510550299250924469288819",

                    // The generator mulplicative group g as a decimal string
                    "generator" => "2",

                    // The derived key K = H(N|g) as a hexadecimal string
                    "key" => "5b9e8ef059c6b32ea59fc1d322d37f04aa30bae5aa9003b8321e21ddb04e300",

                    // The hashing algorithm
                    "algorithm" => "sha256"
                ]
            )
        );

        $srpSample

        return 0;
    }
}
