<?php

namespace App\DataFixtures;


use App\Entity\CharacterChoice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CharacterChoiceFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $fightersData = $this->getFighters();

        // foreach($this->getFighters() as $Fighters) {
        //     dump(array_keys($Fighters));
        //     dump($Fighters);
        // }

        foreach ($this->getFighters() as $key => $Fighters) {
            $fighter = new CharacterChoice();
            $fighter->setName($key);
            $fighter->setWeight($Fighters["weight"]);
            $fighter->setSpeed($Fighters["speed"]);
            $fighter->setTier($Fighters["tier"]);
            $fighter->setIterationNumber($Fighters["iteration_number"]);
            $fighter->setImagePath($Fighters["image_path"]);
            $serie = $this->getReference($Fighters["serie"]);
            $fighter->setSerie($serie);
            $manager->persist($fighter);
            $this->addReference($Fighters["iteration_number"], $fighter);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [SerieFixtures::class];
    }

    private function getFighters(): array
    {
        return [
            "Mario" => [
                "serie" => "Mario",
                "weight" => "Middleweight",
                "speed" => "Average+",
                "tier" => "A+",
                "iteration_number" => "01",
                "image_path" => "Mario_SSBU.png"

            ], "Donkey Kong" => [
                "serie" => "Donkey Kong",
                "weight" => "Heavyweight",
                "speed" => "Slow",
                "tier" => "C-",
                "iteration_number" => "02",
                "image_path" => "Donkey_Kong_SSBU.png"
            ],
            "Link" => [
                "serie" => "The Legend of Zelda",
                "weight" => "Heavyweight",
                "speed" => "Average+",
                "tier" => "B-",
                "iteration_number" => "03",
                "image_path" => "Link_SSBU.png"

            ],
            "Samus" => [
                "serie" => "Metroid",
                "weight" => "Heavyweight",
                "speed" => "Average-",
                "tier" => "A+",
                "iteration_number" => "04",
                "image_path" => "Samus_SSBU.png"

            ],
            "Dark Samus" => [
                "serie" => "Metroid",
                "weight" => "Heavyweight",
                "speed" => "Average-",
                "tier" => "A+",
                "iteration_number" => "04E",
                "image_path" => "Dark_Samus_SSBU.png"

            ],
            "Yoshi" => [
                "serie" => "Yoshi",
                "weight" => "Heavyweight",
                "speed" => "Fast",
                "tier" => "A+",
                "iteration_number" => "05",
                "image_path" => "Yoshi_SSBU.png"

            ],
            "Kirby" => [
                "serie" => "Kirby",
                "weight" => "Lightweight",
                "speed" => "Slow+",
                "tier" => "D+",
                "iteration_number" => "06",
                "image_path" => "Kirby_SSBU.png"

            ],
            "Fox" => [
                "serie" => "Star Fox",
                "weight" => "Lightweight",
                "speed" => "Fast",
                "tier" => "S",
                "iteration_number" => "07",
                "image_path" => "Fox_SSBU.png"

            ],
            "Pikachu" => [
                "serie" => "Pokémon",
                "weight" => "Lightweight",
                "speed" => "Average+",
                "tier" => "S-",
                "iteration_number" => "08",
                "image_path" => "Pikachu_SSBU.png"

            ],
            "Luigi" => [
                "serie" => "Mario",
                "weight" => "Middleweight",
                "speed" => "Average",
                "tier" => "B+",
                "iteration_number" => "09",
                "image_path" => "Luigi_SSBU.png"

            ],
            "Ness" => [
                "serie" => "EarthBound",
                "weight" => "Middleweight",
                "speed" => "Average-",
                "tier" => "B+",
                "iteration_number" => "10",
                "image_path" => "Ness_SSBU.png"

            ],
            "Captain Falcon" => [
                "serie" => "F-Zero",
                "weight" => "Heavyweight",
                "speed" => "Fast",
                "tier" => "B+",
                "iteration_number" => "11",
                "image_path" => "Captain_Falcon_SSBU.png"

            ],
            "Jigglypuff" => [
                "serie" => "Pokémon",
                "weight" => "Lightweight",
                "speed" => "Average-",
                "tier" => "C+",
                "iteration_number" => "12",
                "image_path" => "Jigglypuff_SSBU.png"

            ],
            "Peach" => [
                "serie" => "Mario",
                "weight" => "Lightweight",
                "speed" => "Slow",
                "tier" => "S",
                "iteration_number" => "13",
                "image_path" => "Peach_SSBU.png"

            ],
            "Daisy" => [
                "serie" => "Mario",
                "weight" => "Lightweight",
                "speed" => "Slow",
                "tier" => "S",
                "iteration_number" => "13E",
                "image_path" => "Daisy_SSBU.png"

            ],
            "Bowser" => [
                "serie" => "Mario",
                "weight" => "Heavyweight",
                "speed" => "Slow",
                "tier" => "B-",
                "iteration_number" => "14",
                "image_path" => "Bowser_SSBU.png"

            ],
            "Ice Climbers" => [
                "serie" => "Ice Climber",
                "weight" => "Middleweight",
                "speed" => "Average+",
                "tier" => "B-",
                "iteration_number" => "15",
                "image_path" => "Ice_Climbers_SSBU.png"

            ],
            "Sheik" => [
                "serie" => "The Legend of Zelda",
                "weight" => "Lightweight",
                "speed" => "Fast",
                "tier" => "A-",
                "iteration_number" => "16",
                "image_path" => "Sheik_SSBU.png"

            ],
            "Zelda" => [
                "serie" => "The Legend of Zelda",
                "weight" => "Lightweight",
                "speed" => "Average+",
                "tier" => "D+",
                "iteration_number" => "17",
                "image_path" => "Zelda_SSBU.png"

            ],
            "Dr. Mario" => [
                "serie" => "Mario",
                "weight" => "Middleweight",
                "speed" => "Slow",
                "tier" => "D+",
                "iteration_number" => "18",
                "image_path" => "Dr._Mario_SSBU.png"

            ],
            "Pichu" => [
                "serie" => "Pokémon",
                "weight" => "Lightweight",
                "speed" => "Fast",
                "tier" => "B-",
                "iteration_number" => "19",
                "image_path" => "Pichu_SSBU.png"

            ],
            "Falco" => [
                "serie" => "Star Fox",
                "weight" => "Lightweight",
                "speed" => "Fast-",
                "tier" => "B+",
                "iteration_number" => "20",
                "image_path" => "Falco_SSBU.png"

            ],
            "Marth" => [
                "serie" => "Fire Emblem",
                "weight" => "Middleweight",
                "speed" => "Fast",
                "tier" => "B-",
                "iteration_number" => "21",
                "image_path" => "Marth_SSBU.png"

            ],
            "Lucina" => [
                "serie" => "Fire Emblem",
                "weight" => "Middleweight",
                "speed" => "Fast",
                "tier" => "A+",
                "iteration_number" => "21E",
                "image_path" => "Lucina_SSBU.png"
            ],
            "Young Link" => [
                "serie" => "The Legend of Zelda",
                "weight" => "Lightweight",
                "speed" => "Average",
                "tier" => "A-",
                "iteration_number" => "22",
                "image_path" => "Young_Link_SSBU.png"

            ],
            "Ganondorf" => [
                "serie" => "The Legend of Zelda",
                "weight" => "Heavyweight",
                "speed" => "Slow",
                "tier" => "D-",
                "iteration_number" => "23",
                "image_path" => "Ganondorf_SSBU.png"

            ],
            "Mewtwo" => [
                "serie" => "Pokémon",
                "weight" => "Lightweight",
                "speed" => "Fast",
                "tier" => "C+",
                "iteration_number" => "24",
                "image_path" => "Mewtwo_SSBU.png"
            ],
            "Roy" => [
                "serie" => "Fire Emblem",
                "weight" => "Middleweight",
                "speed" => "Fast",
                "tier" => "S",
                "iteration_number" => "25",
                "image_path" => "Roy_SSBU.png"
            ],
            "Chrom" => [
                "serie" => "Fire Emblem",
                "weight" => "Middleweight",
                "speed" => "Fast",
                "tier" => "B+",
                "iteration_number" => "25E",
                "image_path" => "Chrom_SSBU.png"
            ],
            "Mr. Game & Watch" => [
                "serie" => "Game & Watch",
                "weight" => "Lightweight",
                "speed" => "Fast",
                "tier" => "S-",
                "iteration_number" => "26",
                "image_path" => "Mr._Game_&_Watch_SSBU.png"
            ],
            "Meta Knight" => [
                "serie" => "Kirby",
                "weight" => "Lightweight",
                "speed" => "Fast",
                "tier" => "B-",
                "iteration_number" => "27",
                "image_path" => "Meta_Knight_SSBU.png"
            ],
            "Pit" => [
                "serie" => "Kid Icarus",
                "weight" => "Middleweight",
                "speed" => "Average+",
                "tier" => "B-",
                "iteration_number" => "28",
                "image_path" => "Pit_SSBU.png"
            ],
            "Dark Pit" => [
                "serie" => "Kid Icarus",
                "weight" => "Middleweight",
                "speed" => "Average+",
                "tier" => "B-",
                "iteration_number" => "28E",
                "image_path" => "Dark_Pit_SSBU.png"
            ],
            "Zero Suit Samus" => [
                "serie" => "Metroid",
                "weight" => "Lightweight",
                "speed" => "Fast",
                "tier" => "A+",
                "iteration_number" => "29",
                "image_path" => "Zero_Suit_Samus_SSBU.png"
            ],
            "Wario" => [
                "serie" => "Wario",
                "weight" => "Heavyweight",
                "speed" => "Average+",
                "tier" => "A+",
                "iteration_number" => "30",
                "image_path" => "Wario_SSBU.png"
            ],
            "Snake" => [
                "serie" => "Metal Gear",
                "weight" => "Heavyweight",
                "speed" => "Slow",
                "tier" => "S-",
                "iteration_number" => "31",
                "image_path" => "Snake_SSBU.png"
            ],
            "Ike" => [
                "serie" => "Fire Emblem",
                "weight" => "Heavyweight",
                "speed" => "Average+",
                "tier" => "C-",
                "iteration_number" => "32",
                "image_path" => "Ike_SSBU.png"
            ],
            "Pokémon Trainer" => [
                "serie" => "Pokémon",
                "weight" => "Light - Middle - Heavy",
                "speed" => "Fast - Avrage- - Slow",
                "tier" => "A+",
                "iteration_number" => "33-34-35",
                "image_path" => "Pokémon_Trainer_SSBU.png"
            ],
            "Diddy Kong" => [
                "serie" => "Donkey Kong",
                "weight" => "Middleweight",
                "speed" => "Fast-",
                "tier" => "S-",
                "iteration_number" => "36",
                "image_path" => "Diddy_Kong_SSBU.png"
            ],
            "Lucas" => [
                "serie" => "EarthBound",
                "weight" => "Middleweight",
                "speed" => "Average-",
                "tier" => "C+",
                "iteration_number" => "37",
                "image_path" => "Lucas_SSBU.png"
            ],
            "Sonic" => [
                "serie" => "Sonic the Hedgehog",
                "weight" => "Lightweight",
                "speed" => "Fast",
                "tier" => "S",
                "iteration_number" => "38",
                "image_path" => "Sonic_SSBU.png"
            ],
            "King Dedede" => [
                "serie" => "Kirby",
                "weight" => "Heavyweight",
                "speed" => "Slow",
                "tier" => "D+",
                "iteration_number" => "39",
                "image_path" => "King_Dedede_SSBU.png"
            ],
            "Olimar" => [
                "serie" => "Pikmin",
                "weight" => "Lightweight",
                "speed" => "Average-",
                "tier" => "A-",
                "iteration_number" => "40",
                "image_path" => "Olimar_SSBU.png"
            ],
            "Lucario" => [
                "serie" => "Pokémon",
                "weight" => "Middleweight",
                "speed" => "Average",
                "tier" => "C-",
                "iteration_number" => "41",
                "image_path" => "Lucario_SSBU.png"
            ],
            "R.O.B" => [
                "serie" => "R.O.B.",
                "weight" => "Heavyweight",
                "speed" => "Average+",
                "tier" => "S",
                "iteration_number" => "42",
                "image_path" => "R.O.B._SSBU.png"
            ],
            "Toon Link" => [
                "serie" => "The Legend of Zelda",
                "weight" => "Middleweight",
                "speed" => "Average",
                "tier" => "B-",
                "iteration_number" => "43",
                "image_path" => "Toon_Link_SSBU.png"
            ],
            "Wolf" => [
                "serie" => "Star Fox",
                "weight" => "Middleweight",
                "speed" => "Average",
                "tier" => "S",
                "iteration_number" => "44",
                "image_path" => "Wolf_SSBU.png"
            ],
            "Villager" => [
                "serie" => "Animal Crossing",
                "weight" => "Middleweight",
                "speed" => "Slow",
                "tier" => "C-",
                "iteration_number" => "45",
                "image_path" => "Villager_SSBU.png"
            ],
            "Mega Man" => [
                "serie" => "Mega Man",
                "weight" => "Heavyweight",
                "speed" => "Average",
                "tier" => "A-",
                "iteration_number" => "46",
                "image_path" => "Mega_Man_SSBU.png"
            ],
            "Wii Fit Trainer" => [
                "serie" => "Wii Fit",
                "weight" => "Middleweight",
                "speed" => "Fast-",
                "tier" => "C+",
                "iteration_number" => "47",
                "image_path" => "Wii_Fit_Trainer_SSBU.png"
            ],
            "Rosalina & Luma" => [
                "serie" => "Mario",
                "weight" => "Lightweight",
                "speed" => "Average-",
                "tier" => "B-",
                "iteration_number" => "48",
                "image_path" => "Rosalina_&_Luma_SSBU.png"
            ],
            "Little Mac" => [
                "serie" => "Punch-Out!!",
                "weight" => "Lightweight",
                "speed" => "Fast",
                "tier" => "D-",
                "iteration_number" => "49",
                "image_path" => "Little_Mac_SSBU.png"
            ],
            "Greninja" => [
                "serie" => "Pokémon",
                "weight" => "Lightweight",
                "speed" => "Fast",
                "tier" => "A-",
                "iteration_number" => "50",
                "image_path" => "Greninja_SSBU.png"
            ],
            "Mii Brawler" => [
                "serie" => "Super Smash Bros.",
                "weight" => "Middleweight",
                "speed" => "Fast",
                "tier" => "A-",
                "iteration_number" => "51",
                "image_path" => "Mii_Brawler_SSBU.png"
            ],
            "Mii Swordfighter" => [
                "serie" => "Super Smash Bros.",
                "weight" => "Middleweight",
                "speed" => "Average",
                "tier" => "D+",
                "iteration_number" => "52",
                "image_path" => "Mii_Swordfighter_SSBU.png"
            ],
            "Mii Gunner" => [
                "serie" => "Super Smash Bros.",
                "weight" => "Heavyweight",
                "speed" => "Slow",
                "tier" => "C+",
                "iteration_number" => "53",
                "image_path" => "Mii_Gunner_SSBU.png"
            ],
            "Palutena" => [
                "serie" => "Kid Icarus",
                "weight" => "Middleweight",
                "speed" => "Average+",
                "tier" => "S-",
                "iteration_number" => "54",
                "image_path" => "Palutena_SSBU.png"
            ],
            "Pac-Man" => [
                "serie" => "Pac-Man",
                "weight" => "Middleweight",
                "speed" => "Average-",
                "tier" => "S-",
                "iteration_number" => "55",
                "image_path" => "Pac-Man_SSBU.png"
            ],
            "Robin" => [
                "serie" => "Fire Emblem",
                "weight" => "Middleweight",
                "speed" => "Slow",
                "tier" => "C+",
                "iteration_number" => "56",
                "image_path" => "Robin_SSBU.png"
            ],
            "Shulk" => [
                "serie" => "Xenoblade Chronicles",
                "weight" => "Middleweight",
                "speed" => "Average",
                "tier" => "S-",
                "iteration_number" => "57",
                "image_path" => "Shulk_SSBU.png"
            ],
            "Bowser Jr." => [
                "serie" => "Mario",
                "weight" => "Heavyweight",
                "speed" => "Slow+",
                "tier" => "C-",
                "iteration_number" => "58",
                "image_path" => "Bowser_Jr._SSBU.png"
            ],
            "Duck Hunt" => [
                "serie" => "Duck Hunt",
                "weight" => "Lightweight",
                "speed" => "Average",
                "tier" => "C-",
                "iteration_number" => "59",
                "image_path" => "Duck_Hunt_SSBU.png"
            ],
            "Ryu" => [
                "serie" => "Street Fighter",
                "weight" => "Heavyweight",
                "speed" => "Average",
                "tier" => "A-",
                "iteration_number" => "60",
                "image_path" => "Ryu_SSBU.png"
            ],
            "Ken" => [
                "serie" => "Street Fighter",
                "weight" => "Heavyweight",
                "speed" => "Average",
                "tier" => "A-",
                "iteration_number" => "60E",
                "image_path" => "Ken_SSBU.png"
            ],
            "Cloud" => [
                "serie" => "Final Fantasy",
                "weight" => "Middleweight",
                "speed" => "Average+",
                "tier" => "S-",
                "iteration_number" => "61",
                "image_path" => "Cloud_SSBU.png"
            ],
            "Corrin" => [
                "serie" => "Fire Emblem",
                "weight" => "Middleweight",
                "speed" => "Average",
                "tier" => "B+",
                "iteration_number" => "62",
                "image_path" => "Corrin_SSBU.png"
            ],
            "Bayonetta" => [
                "serie" => "Bayonetta",
                "weight" => "Lightweight",
                "speed" => "Average",
                "tier" => "B+",
                "iteration_number" => "63",
                "image_path" => "Bayonetta_SSBU.png"
            ],
            "Inkling" => [
                "serie" => "Splatoon",
                "weight" => "Middleweight",
                "speed" => "Fast",
                "tier" => "B+",
                "iteration_number" => "64",
                "image_path" => "Inkling_SSBU.png"
            ],
            "Ridley" => [
                "serie" => "Metroid",
                "weight" => "Heavyweight",
                "speed" => "Average",
                "tier" => "C+",
                "iteration_number" => "65",
                "image_path" => "Ridley_SSBU.png"
            ],
            "Simon" => [
                "serie" => "Castlevania",
                "weight" => "Heavyweight",
                "speed" => "Slow",
                "tier" => "C-",
                "iteration_number" => "66",
                "image_path" => "Simon_SSBU.png"
            ],
            "Richter" => [
                "serie" => "Castlevania",
                "weight" => "Heavyweight",
                "speed" => "Slow",
                "tier" => "C-",
                "iteration_number" => "66E",
                "image_path" => "Richter_SSBU.png"
            ],
            "King K. Rool" => [
                "serie" => "Donkey Kong",
                "weight" => "Heavyweight",
                "speed" => "Slow",
                "tier" => "D+",
                "iteration_number" => "67",
                "image_path" => "King_K._Rool_SSBU.png"
            ],
            "Isabelle" => [
                "serie" => "Animal Crossing",
                "weight" => "Lightweight",
                "speed" => "Slow",
                "tier" => "D+",
                "iteration_number" => "68",
                "image_path" => "Isabelle_SSBU.png"
            ],
            "Inceneroar" => [
                "serie" => "Pokémon",
                "weight" => "Heavyweight",
                "speed" => "Slow",
                "tier" => "C+",
                "iteration_number" => "69",
                "image_path" => "Incineroar_SSBU.png"
            ],
            "Piranha Plant" => [
                "serie" => "Mario",
                "weight" => "Heavyweight",
                "speed" => "Slow",
                "tier" => "D+",
                "iteration_number" => "70",
                "image_path" => "Piranha_Plant_SSBU.png"
            ],
            "Joker" => [
                "serie" => "Persona",
                "weight" => "Lightweight",
                "speed" => "Fast",
                "tier" => "S+",
                "iteration_number" => "71",
                "image_path" => "Joker_SSBU.png"
            ],
            "Hero" => [
                "serie" => "Dragon Quest",
                "weight" => "Middleweight",
                "speed" => "Average",
                "tier" => "B+",
                "iteration_number" => "72",
                "image_path" => "Hero_SSBU.webp"
            ],
            "Banjo & Kazooie" => [
                "serie" => "Banjo-Kazooie",
                "weight" => "Heavyweight",
                "speed" => "Slow+",
                "tier" => "C-",
                "iteration_number" => "73",
                "image_path" => "Banjo_&_Kazooie_SSBU.png"
            ],
            "Terry" => [
                "serie" => "Fatal Fury",
                "weight" => "Heavyweight",
                "speed" => "Average+",
                "tier" => "A-",
                "iteration_number" => "74",
                "image_path" => "Terry_SSBU.png"
            ],
            "Byleth" => [
                "serie" => "Fire Emblem",
                "weight" => "Middleweight",
                "speed" => "Average",
                "tier" => "A-",
                "iteration_number" => "75",
                "image_path" => "Byleth_SSBU.png"
            ],
            "Min Min" => [
                "serie" => "ARMS",
                "weight" => "Heavyweight",
                "speed" => "Average-",
                "tier" => "S-",
                "iteration_number" => "76",
                "image_path" => "Min_Min_SSBU.png"
            ],
            "Steve" => [
                "serie" => "Minecraft",
                "weight" => "Middleweight",
                "speed" => "Slow",
                "tier" => "S+",
                "iteration_number" => "77",
                "image_path" => "Steve_SSBU.png"
            ],
            "Sephiroth" => [
                "serie" => "Final Fantasy",
                "weight" => "Lightweight",
                "speed" => "Average+",
                "tier" => "A+",
                "iteration_number" => "78",
                "image_path" => "Sephiroth_SSBU.png"
            ],
            "Pyra & Mythra" => [
                "serie" => "Xenoblade Chronicles",
                "weight" => "Middleweight",
                "speed" => "Slow-Fast",
                "tier" => "S+",
                "iteration_number" => "79-80",
                "image_path" => "Pyra_&_Mythra_SSBU.png"
            ],
            "Kazuya" => [
                "serie" => "Tekken",
                "weight" => "Heavyweight",
                "speed" => "Slow",
                "tier" => "S",
                "iteration_number" => "81",
                "image_path" => "Kazuya_SSBU.webp"
            ],
            "Sora" => [
                "serie" => "Kingdom Hearts",
                "weight" => "Lightweight",
                "speed" => "Average+",
                "tier" => "A-",
                "iteration_number" => "82",
                "image_path" => "Sora_SSBU.png"
            ],
            "Character Fixture" => [
                "serie" => "Castlevania",
                "weight" => "Heavyweight",
                "speed" => "Average+",
                "tier" => "A+",
                "iteration_number" => "83",
                "image_path" => "Mario_SSBU.png"
            ],
        ];
    }
}
