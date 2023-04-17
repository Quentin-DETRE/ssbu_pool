<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;

#[AsCommand(
    name: 'app:image-optimizer',
    description: 'Add a short description for your command',
)]
class ImageOptimizerCommand extends Command
{
    private const MAX_WIDTH = 200;
    private const MAX_HEIGHT = 150;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $finder = new Finder();
        $fileSystem = new Filesystem();
        $imagine = new Imagine();
        $finder->in('public/fighters');
        $finder->files()->name(['*.png', '*.webp']);
        foreach($finder as $file) {
            $fullFile = "public/fighters/". $file->getRelativePathname();
            $reduceFile = "public/fighters/250_" . $file->getRelativePathname();
            if(!$fileSystem->exists($reduceFile)) 
            {
                list($iwidth, $iheight) = getimagesize($fullFile);
                $ratio = $iwidth / $iheight;
                $width = self::MAX_WIDTH;
                $height = self::MAX_HEIGHT;
                if ($width / $height > $ratio) {
                    $width = $height * $ratio;
                } else {
                    $height = $width / $ratio;
                }

                $photo = $imagine->open($fullFile);
                $photo->resize(new Box($width, $height))->save($reduceFile);
            }
        }


        $io->success("Images were optimised with success");

        return Command::SUCCESS;
    }
}
