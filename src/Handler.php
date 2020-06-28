<?php
namespace Sahtepetrucci\PlayTheWeb;

use Sahtepetrucci\PlayTheWeb\Library\MusicTheory;
use Sahtepetrucci\PlayTheWeb\Data;
use Sahtepetrucci\PlayTheWeb\Song;

class Handler {

    private $musicTheory;
    private $data;
    public $song;

    public function __construct() {
        $this->musicTheory = new MusicTheory();
        $this->data = new Data();
        $this->song = new Song();
    }

    public function run($tone = null, $mode = null)
    {
        $html = file_get_contents(__DIR__ . '/../samples/facebook_sample.html');
        $classNames = $this->getMostPopularClassNamesFrom($html);
        
        $this->song->generateFrom($classNames);
        $this->applyOverrides($tone, $mode);

        // Set the scale of the song by using the selected tone in the selected mode
        $this->song->scale = $this->musicTheory->getScaleByName($this->song->tone, $this->song->mode);
        $this->song->createComposition();

        return $this->song;
    }

    public function getMostPopularClassNamesFrom($content) {
        preg_match_all('/(class)+="([a-zA-Z0-9-_ ]+)"/', $content, $matches); // grab all the class names
        $list = array_filter(explode(" ", implode(" ", $matches[2]))); // make a list of the class names
        
        $popular_classes = array_count_values($list); // make a list of classes with their usage counts
        arsort($popular_classes); // sort them by their usage count

        return $popular_classes;
    }

    public function applyOverrides($tone, $mode) {
        if ($tone && in_array($tone, $this->data->notes)) {
            $this->song->tone = $tone;
        }

        if ($mode && in_array($mode, $this->data->modes)) {
            $this->song->mode = $mode;
        }

    }
}