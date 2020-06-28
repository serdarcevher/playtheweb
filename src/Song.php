<?php
namespace Sahtepetrucci\PlayTheWeb;
use Sahtepetrucci\PlayTheWeb\Data;

class Song {

    public $tone;
    public $mode;
    public $scale;
    public $pedal;
    public $tempo;
    public $duration;
    public $composition;
    
    private $body;
    private $data;

    public function __construct() {
        $this->tone = 'C';
        $this->mode = 'major';
        $this->scale = [];
        $this->pedal = 0;
        $this->tempo = 60;
        $this->duration = 0; // in milliseconds

        $this->body = [
            'length' => 0,
            'elements' => []
        ];

        $this->data = new Data();
    }

    public function generateFrom($classNames) {
        $this->createBodyFrom($classNames);

        if ($this->body['length']) {

            $this->determineTone();
            $this->determineMode();
            $this->determineTempo();
            $this->determinePedalPoint();
        }
    }

    public function createBodyFrom($classNames) {

        $i = 0;
        $totalCount = 0;

        foreach ($classNames as $class => $count):
            $wordLength = strlen($class); // will determine the length of the corresponding note
            $this->body['length']+= $wordLength; // will be a determiner

            $firstLetter = substr($class, 0, 1);
            $noteIndex = ord($firstLetter) % 7; // the index of the first letter's corresponding note in the notes array

            $this->body['elements'][] = [
                "class" => $class,
                "index" => $noteIndex, // this index will be used to get the respective note from the scale
                "length" => $wordLength
            ]; // the current class is now a part of the body

        endforeach;
    }

    public function determineTone() {
        $firstClassLength = $this->body['elements'][0]['length'] % 7;
        $this->tone = $this->data->notes[$firstClassLength]; // the tone of the piece

        /*
        if($selected_tone != "automatic") {
            $this->song->tone = $selected_tone;
        }
        */
    }

    public function determineTempo() {
        $tempo = (1 / $this->body['length']) * 50000;
        if($tempo <= 60) { // change the default song tempo in case the result is smaller than 60
            $this->tempo = round($tempo);
        }
    }

    public function determineMode() {
        $modeIndex = $this->body['length'] % 21;
        $this->mode = $this->data->modes[$modeIndex];
    }

    public function determinePedalPoint() {
        $this->pedal = $this->data->frequencies[$this->tone] / 2;
    }

    public function createComposition() {
        $this->composition = [];

        $i = 0;
        $rest = $this->body['length'] % 8;
        if ($rest < 3) {
            $rest = 3;
        }

        $waitFor = 0;

        foreach ($this->body['elements'] as $element):
            $i++;
            $note = $this->scale[$element['index']] ?? $this->scale[0];
            $frequency = $i % $rest == 0 ? 0 : $this->data->frequencies[$note];
            $duration = $element['length'] * $this->tempo;
            $this->duration += $duration;

            $this->composition[] = [
                'word' => $element['class'],
                'frequency' => $frequency,
                'duration' => $element['length'] * $this->tempo,
                'waitFor' => $this->duration
            ];
            $word = $element['class'];
        endforeach;

        $this->duration+=1000;

        //unset($this->body);

    }
}