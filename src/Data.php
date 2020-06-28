<?php
namespace Sahtepetrucci\PlayTheWeb;

class Data {
    
    public $notes;
    public $modes;
    public $frequencies;

    public function __construct() {
        $this->notes = ["C","D","E","F","G","A","B"];
        $this->frequencies = $this->getFrequencies();
        $this->modes = $this->getModes();
    }

    public function getFrequencies() {
        return [
            "C" => "523.28",
            "C#" => "554.40",
            "D" => "587.36",
            "D#" => "622.24",
            "E" => "659.28",
            "F" => "698.48",
            "F#" => "740",
            "G" => "784",
            "G#" => "830.61",
            "A" => "440",
            "A#" => "466.16",
            "B" => "493.92",
        ];
    }

     public function getModes() {
        return [
            0 => "major",
            1 => "ionian",
            2 => "aeolian",
            3 => "dorian",
            4 => "phrygian",
            5 => "lydian",
            6 => "mixolydian",
            7 => "locrian",
            8 => "melodic minor asc",
            9 => "melodic minor desc",
            10 => "chromatic",
            11 => "pentatonic major",
            12 => "whole tone",
            13 => "pentatonic minor",
            14 => "pentatonic blues",
            15 => "pentatonic neutral",
            16 => "lydian augmented",
            17 => "lydian minor",
            18 => "lydian diminished",
            19 => "major blues",
            20 => "dominant pentatonic",
            21 => "blues"
        ];
    }
}