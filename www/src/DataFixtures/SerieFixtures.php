<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SerieFixtures extends Fixture 
{
    public function load(ObjectManager $manager): void
    {
        
        foreach($this->getCompleteSeries() as $serieName)
        {
            $serie = new Serie();
            $serie->setName($serieName["name"]);
            $serie->setImagePath($serieName["image"]);
            $manager->persist($serie);
            //dd($serie, $serieName);
            $this->addReference($serieName["name"],$serie);
        }
        $manager->flush();
    }

    private function getCompleteSeries(){

        return 
        [
            0 =>  [
              "name" => "Super Smash Bros.",
              "image" => "SmashBrosSymbol.svg"
            ],
            1 =>  [
              "name" => "Mario",
              "image" => "MarioSymbol.svg"
            ],
            2 =>  [
              "name" => "Yoshi",
              "image" => "YoshiSymbol.svg"
            ],
            3 =>  [
              "name" => "Donkey Kong",
              "image" => "DKSymbol.svg"
            ],
            4 =>  [
              "name" => "The Legend of Zelda",
              "image" => "ZeldaSymbol.svg"
            ],
            5 =>  [
              "name" => "Metroid",
              "image" => "MetroidSymbol.svg"
            ],
            6 =>  [
              "name" => "Kirby",
              "image" => "KirbySymbol.svg"
            ],
            7 =>  [
              "name" => "Star Fox",
              "image" => "StarFoxSymbol.svg"
            ],
            8 =>  [
              "name" => "Pokémon",
              "image" => "PokemonSymbol.svg"
            ],
            9 =>  [
              "name" => "F-Zero",
              "image" => "FZeroSymbol.svg"
            ],
            10 =>  [
              "name" => "EarthBound",
              "image" => "EarthboundSymbol.svg"
            ],
            11 =>  [
              "name" => "Ice Climber",
              "image" => "IceClimberSymbol.svg"
            ],
            12 =>  [
              "name" => "Fire Emblem",
              "image" => "FireEmblemSymbol.svg"
            ],
            13 =>  [
              "name" => "Game & Watch",
              "image" => "Game%26WatchSymbol.svg"
            ],
            14 =>  [
              "name" => "Kid Icarus",
              "image" => "KidIcarusSymbol.svg"
            ],
            15 =>  [
              "name" => "Wario",
              "image" => "WarioSymbol.svg"
            ],
            16 =>  [
              "name" => "Pikmin",
              "image" => "PikminSymbol.svg"
            ],
            17 =>  [
              "name" => "R.O.B.",
              "image" => "ROBSymbol.svg"
            ],
            18 =>  [
              "name" => "Animal Crossing",
              "image" => "AnimalCrossingSymbol.svg"
            ],
            19 =>  [
              "name" => "Wii Fit",
              "image" => "WiiFitSymbol.svg"
            ],
            20 =>  [
              "name" => "Punch-Out!!",
              "image" => "PunchOutSymbol.svg"
            ],
            21 =>  [
              "name" => "Xenoblade Chronicles",
              "image" => "XenobladeSymbol.svg"
            ],
            22 =>  [
              "name" => "Duck Hunt",
              "image" => "DuckHuntSymbol.svg"
            ],
            23 =>  [
              "name" => "Splatoon",
              "image" => "SplatoonSymbol.svg"
            ],
            24 =>  [
              "name" => "ARMS",
              "image" => "ARMSSymbol.svg"
            ],
            25 =>  [
              "name" => "Metal Gear",
              "image" => "MetalGearSymbol.svg"
            ],
            26 =>  [
              "name" => "Sonic the Hedgehog",
              "image" => "SonicSymbol.svg"
            ],
            27 =>  [
              "name" => "Mega Man",
              "image" => "MegaManSymbol.svg"
            ],
            28 =>  [
              "name" => "Pac-Man",
              "image" => "PacManSymbol.svg"
            ],
            29 =>  [
              "name" => "Street Fighter",
              "image" => "StreetFighterSymbol.svg"
            ],
            30 =>  [
              "name" => "Final Fantasy",
              "image" => "FinalFantasySymbol.svg"
            ],
            31 =>  [
              "name" => "Bayonetta",
              "image" => "BayonettaSymbol.svg"
            ],
            32 =>  [
              "name" => "Castlevania",
              "image" => "CastlevaniaSymbol.svg"
            ],
            33 =>  [
              "name" => "Persona",
              "image" => "PersonaSymbol.svg"
            ],
            34 =>  [
              "name" => "Dragon Quest",
              "image" => "DragonQuestSymbol.svg"
            ],
            35 =>  [
              "name" => "Banjo-Kazooie",
              "image" => "BanjoKazooieSymbol.svg"
            ],
            36 =>  [
              "name" => "Fatal Fury",
              "image" => "FatalFurySymbol.svg"
            ],
            37 =>  [
              "name" => "Minecraft",
              "image" => "MinecraftSymbol.svg"
            ],
            38 =>  [
              "name" => "Tekken",
              "image" => "TekkenSymbol.svg"
            ],
            39 =>  [
              "name" => "Kingdom Hearts",
              "image" => "KingdomHeartsSymbol.svg"
            ],
            40 =>  [
              "name" => "Nintendo DS",
              "image" => "DSSymbol.svg"
            ],
            41 =>  [
              "name" => "Electroplankton",
              "image" => "ElectroplanktonSymbol.svg"
            ],
            42 =>  [
              "name" => "Balloon Fight",
              "image" => "BalloonFightSymbol.svg"
            ],
            43 =>  [
              "name" => "Nintendogs",
              "image" => "NintendogsSymbol.svg"
            ],
            44 =>  [
              "name" => "Mii",
              "image" => "MiiSymbol.svg"
            ],
            45 =>  [
              "name" => "StreetPass Mii Plaza",
              "image" => "FindMiiSymbol.svg"
            ],
            46 =>  [
              "name" => "Tomodachi",
              "image" => "TomodachiSymbol.svg"
            ],
            47 =>  [
              "name" => "Wrecking Crew",
              "image" => "WreckingCrewSymbol.svg"
            ],
            48 =>  [
              "name" => "Pilotwings",
              "image" => "PilotwingsSymbol.svg"
            ],
            49 =>  [
              "name" => "Wii Sports",
              "image" => "WiiSportsSymbol.svg"
            ],
            50 =>  [
              "name" => "Miiverse",
              "image" => "MiiverseSymbol.svg"
            ]
        ];
    }
}
