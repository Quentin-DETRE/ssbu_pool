<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DomCrawler\Crawler;

#[AsCommand(
    name: 'app:import-character',
    description: 'Add a short description for your command',
)]
class ImportCharacterCommand extends Command
{
    protected function configure(): void
    {
        
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
       
        $url = 'https://www.ssbwiki.com/Fighter_number';

        $crawler = new Crawler(file_get_contents($url));

        $result = $crawler->filter('.wikitable > tbody > tr > td > a')->previousAll()->each(function (Crawler $node, $i) {
    
            // $img =  $node->filter('img')->attr('src');
          
            // $tmp = explode("/", $img);
            // $imgName = $tmp[count($tmp)-1];

            // $dir = __DIR__.'/../../public/serie';
            // $path =  $dir."/".$imgName;

            // if(!file_exists($path)){
            //     $file = file_get_contents($img);
            //     file_put_contents($path, $file);
            // }
            // dump($node->text());
            // dump($i);
            // die();
            return [
                'name' => $node->filter('a')->text(),
                'image' => ""
            ];
            return $node->text();

    
        });

        dump($result);

        $io->success('Hello world');

        return Command::SUCCESS;
    }
}
