<?php
/************************************************************* 
 * This script is developed by Arturs Sosins aka ar2rsawseen, http://webcodingeasy.com 
 * Fee free to distribute and modify code, but keep reference to its creator 
 * 
 * This class implements music theory for generating scales and chords
 * based on interval patterns between notes. User can add custom scale and chord pattern.
 * This class can generate scale notes based on provided scale name and type,
 * generate chord notes based on provided chord name and type,
 * transpose scales, transpose chords,
 * generate all chords that include provided notes,
 * generate all scales that include provided notes,
 * 
 * For more information, examples and online documentation visit:  
 * http://webcodingeasy.com/PHP-classes/Implement-music-theory-to-generate-scale-and-chord-notes 
**************************************************************/

namespace Sahtepetrucci\PlayTheWeb\Library;

class MusicTheory
{
    //use sharps or flats
    private $alt = "sharp";
    //array with sharp and flat type notes
    private $note = array(
                    "sharp" => array("C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"),
                    "flat" => array("C", "Db", "D", "Eb", "E", "F", "Gb", "G", "Ab", "A", "Bb", "B")
                    );
    //array with scale patterns
    private $scales = array(
                "major" => array(2,2,1,2,2,2,1),
                "ionian" => array(2,2,1,2,2,2,1),
                "minor" => array(2,1,2,2,1,2,2),
                "aeolian" => array(2,1,2,2,1,2,2),
                "dorian" => array(2,1,2,2,2,1,2),
                "phrygian" => array(1,2,2,2,1,2,2),
                "lydian" => array(2,2,2,1,2,2,1),
                "mixolydian" => array(2,2,1,2,2,1,2),
                "locrian" => array(1,2,2,1,2,2,2),
                "melodic minor asc" => array(2,1,2,2,2,2,1),
                "melodic minor desc" => array(2,1,2,2,1,2,2),
                "chromatic" => array(1,1,1,1,1,1,1,1,1,1,1,1),
                "pentatonic major" => array(2,2,3,2,3),
                "whole tone" => array(2,2,2,2,2,2),
                "pentatonic minor" => array(3,2,2,3,2),
                "pentatonic blues" => array(3,2,1,1,3),
                "pentatonic neutral" => array(2,3,2,3,2),
                "lydian augmented" => array(2,2,2,2,1,2,1),
                "lydian minor" => array(2,2,2,1,1,2,2),
                "lydian diminished" => array(2,1,3,1,2,2,1),
                "major blues" => array(2,1,1,3,2,3),
                "dominant pentatonic" => array(2,2,3,3,2),
                "blues" => array(3,2,1,1,3,2)
            );
    private $chords = array(
                "major" => array(4,3),
                "minor" => array(3,4),
                "5" => array(7),
                "aug" => array(4,4),
                "dim" => array(3,3),
                "7" => array(4,3,3),
                "sus4" => array(5,2),
                "sus2" => array(2,5),
                "7sus4" => array(5,2,3),
                "6" => array(4,3,2),
                "maj7" => array(4,3,4),
                "9" => array(4,3,3,4),
                "add9" => array(4,3,7),
                "m6" => array(3,4,2),
                "m7" => array(3,4,3),
                "mmaj7" => array(3,4,4),
                "m9" => array(3,4,3,4),
                "11" => array(4,3,3,4,3),
                "13" => array(4,3,3,4,3,4),
                "6add9" => array(4,3,2,5),
                "-5" => array(4,2),
                "7-5" => array(4,2,4),
                "7maj5" => array(4,4,2),
                "maj9" => array(4,3,4,3)
            );
    private $errors = array();
    
    //return array with all notes
    public function get_notes(){
        return $this->note[$this->alt];
    }
    
    //use flat notation
    public function set_flat(){
        $this->alt = "flat";
    }
    
    //use sharp notation
    public function set_sharp(){
        $this->alt = "sharp";
    }
    
    //return array with all defined scale types
    public function get_scale_types(){
        return array_keys($this->scales);
    }
    
    //return array with all defined chord types
    public function get_chord_types(){
        return array_keys($this->chords);
    }
    
    //return all errors and empty error array
    public function get_errors(){
        $arr = $this->errors;
        $this->errors = array();
        return $arr;
    }
    
    //check if all notes are in array
    private function is_in($notes, $arr)
    {
        $is_in = true;
        foreach($notes as $note)
        {
            if(!in_array(ucfirst(strtolower($note)), $arr))
            {
                $is_in = false;
            }
        }
        return $is_in;
    }
    
    /**
     * add new scale type  with interval pattern
     * Example input:
     *    $type = "ionian"; //new scale type name
     *    $pattern_array = array(2,2,1,2,2,2,1);  //interval pattern
     **/
    public function add_scale_type($type, $pattern_array){
        $type = strtolower($type);
        if(!in_array($type, array_keys($this->scales)))
        {
            $this->scales[$type] = $pattern_array;
        }
        else
        {
            $this->errors[] = "Scale type name already used";
            return false;
        }
    }
    
    /**
     * add new chord type with interval pattern
     * Example input:
     *    $type = "major"; //new chord type name
     *    $pattern_array = array(4,3); //interval pattern
     **/
    public function add_chord_type($type, $pattern_array){
        $type = strtolower($type);
        if(!in_array($type, array_keys($this->chords)))
        {
            $this->chords[$type] = $pattern_array;
        }
        else
        {
            $this->errors[] = "Chord type name already used";
            return false;
        }
    }
    
    /**
     * Get scale notes by name and type (optional transpose)
     * Example input:
     *    $name = "C"; //scale name
     *    $type = "ionian"; //scale type
     *    $transpose = -1 //intervals to transpose to (default value 0 doesn't transpose), negative number tranposes down, positive - up
     * Example output:
     *    Array
     *    (
     *       [0] => B
     *       [1] => C#
     *       [2] => D#
     *       [3] => E
     *       [4] => F#
     *       [5] => G#
     *       [6] => A#
     *       [7] => B
     *    )
     **/
    public function getScaleByName($name, $type, $transpose = 0){
        $name = strtolower($name);
        $name = ucfirst($name);
        $type = strtolower($type);
        if(!in_array($name, $this->note['sharp']))
        {
            if(!in_array($name, $this->note['flat']))
            {
                $this->errors[] = "Invalid scale name";
                return array();
            }
            else
            {
                $notes = $this->note["flat"];
            }
        }
        else
        {
            $notes = $this->note["sharp"];
        }
        if(!in_array($type, array_keys($this->scales)))
        {
            $this->errors[] = "Invalid scale type";
            return array();
        }
        $scale = array();
        $start = array_keys($notes, $name);
        $note_sum = sizeof($notes)-1;
        if($start[0] + $transpose < 0)
        {
            $current = ($start[0] + $transpose) + $note_sum + 1;
        }
        else if($start[0] + $transpose > $note_sum)
        {
            $current = (($start[0] + $transpose) - $note_sum) -1;
        }
        else
        {
            $current = $start[0] + $transpose;
        }
        $scale[] = $this->note[$this->alt][$current];
        foreach($this->scales[$type] as $num)
        {
            $current += $num;
            if($current > $note_sum)
            {
                $current -= ($note_sum + 1);
            }
            $scale[] = $this->note[$this->alt][$current];
        }
        return $scale;
    }
    
    /**
     * Get chord notes by name and type (optional transpose)
     * Example input:
     *    $name = "C"; //chord name
     *    $type = "major"; //chord type
     *    $transpose = 2 //intervals to transpose to (default value 0 doesn't transpose), negative number tranposes down, positive - up
     * Example output:
     *    Array
     *    (
     *       [0] => D
     *       [3] => F#
     *       [5] => A
     *    )
     */
    public function get_chord_by_name($name, $type, $transpose = 0){
        $name = strtolower($name);
        $name = ucfirst($name);
        $type = strtolower($type);
        if(!in_array($name, $this->note['sharp']))
        {
            if(!in_array($name, $this->note['flat']))
            {
                $this->errors[] = "Invalid chord name";
                return array();
            }
            else
            {
                $notes = $this->note["flat"];
            }
        }
        else
        {
            $notes = $this->note["sharp"];
        }
        if(!in_array($type, array_keys($this->chords)))
        {
            $this->errors[] = "Invalid chord type";
            return array();
        }
        $chord = array();
        $start = array_keys($notes, $name);
        $note_sum = sizeof($notes)-1;
        if($start[0] + $transpose < 0)
        {
            $current = ($start[0] + $transpose) + $note_sum + 1;
        }
        else if($start[0] + $transpose > $note_sum)
        {
            $current = (($start[0] + $transpose) - $note_sum) -1;
        }
        else
        {
            $current = $start[0] + $transpose;
        }
        $chord[] = $this->note[$this->alt][$current];
        foreach($this->chords[$type] as $num)
        {
            $current += $num;
            if($current > $note_sum)
            {
                $current -= ($note_sum + 1);
            }
            $chord[] = $this->note[$this->alt][$current];
        }
        return $chord;
    }

    /**
     * Get chord names and types by notes
     * Example input:
     *    $notes = array("A","F","C", "E"); //notes
     * Example output:
     *    Array
     *    (
     *        [0] => Array
     *            (
     *                [name] => C
     *                [type] => 13
     *            )
     *    
     *        [1] => Array
     *            (
     *                [name] => D
     *                [type] => m9
     *            )
     *    ...
     *    )
     */
    public function get_chords_by_notes($notes){
        if(!is_array($notes))
        {
            $notes = array($notes);
        }
        $possible = array();
        $chords = $this->get_chord_types();
        for($i = 0; $i < 12; $i++)
        {
            foreach($chords as $chord)
            {
                $temp = $this->get_chord_by_name("C", $chord, $i);
                if($this->is_in($notes, $temp))
                {
                    $count = sizeof($possible);
                    $possible[$count]["name"] = current($temp);
                    $possible[$count]["type"] = $chord;
                }
            }
        }
        return $possible;
    }

    /**
     * Get scale names and types by notes
     * Example input:
     *    $notes = array("C","D","E","G#","C#"); //notes
     * Example output:
     *    Array
     *    (
     *        [0] => Array
     *            (
     *                [name] => C
     *                [type] => chromatic
     *            )
     *    
     *        [1] => Array
     *            (
     *                [name] => C#
     *                [type] =>  chromatic
     *            )
     *    ...
     *    )
     */
    public function get_scales_by_notes($notes){
        if(!is_array($notes))
        {
            $notes = array($notes);
        }
        $possible = array();
        $scales = $this->get_scale_types();
        for($i = 0; $i < 12; $i++)
        {
            foreach($scales as $scale)
            {
                $temp = $this->get_scale_by_name("C", $scale, $i);
                if($this->is_in($notes, $temp))
                {
                    $count = sizeof($possible);
                    $possible[$count]["name"] = current($temp);
                    $possible[$count]["type"] = $scale;
                }
            }
        }
        return $possible;
    }

    /**
     * Get scale names and types by chord names/types
     * Provide different chord names with types, 
     * and receive array with all scale names/types which suits provided chords
     * 
     * Example input:
     *    $chords = array(array("name" => "C", "type" => "major"), array("name" => "A", "type" => "minor")); //chord array
     * Example output:
     *    Array
     *    (
     *        [0] => Array
     *            (
     *                [name] => C
     *                [type] => major
     *            )
     *    
     *        [1] => Array
     *            (
     *                [name] => C
     *                [type] => ionian
     *            )
     *    ...
     *    )
     */
    public function get_scales_by_chords($chords){
        $notes = array();
        foreach($chords as $chord)
        {
            $chord_notes = $this->get_chord_by_name(ucfirst(strtolower($chord["name"])), $chord["type"]);
            $notes = array_merge($notes, $chord_notes);
        }
        $notes = array_unique($notes);
        $possible = array();
        $scales = $this->get_scale_types();
        for($i = 0; $i < 12; $i++)
        {
            foreach($scales as $scale)
            {
                $temp = $this->get_scale_by_name("C", $scale, $i);
                if($this->is_in($notes, $temp))
                {
                    $count = sizeof($possible);
                    $possible[$count]["name"] = current($temp);
                    $possible[$count]["type"] = $scale;
                }
            }
        }
        return $possible;
    }
    
    /**
     * Get chord names and types by scale
     * Provide scale name and type
     * and receive all chord names/types which suits provided scale
     * 
     * Example input:
     *    $scale_name = "C"; //scale name
     *    $scale_type = "ionian" //scale type
     * Example output:
     *    Array
     *    (
     *        [0] => Array
     *            (
     *                [name] => C
     *                [type] => minor
     *            )
     *    
     *        [1] => Array
     *            (
     *                [name] => C
     *                [type] => 5
     *            )
     *    ...
     *    )
     */
    public function get_chords_by_scale($scale_name, $scale_type){
        $scale_notes = $this->get_scale_by_name(ucfirst(strtolower($scale_name)), strtolower($scale_type));
        if(!empty($scale_notes))
        {
            $possible = array();
            $chords = $this->get_chord_types();
            for($i = 0; $i < 12; $i++)
            {
                foreach($chords as $chord)
                {
                    $temp = $this->get_chord_by_name("C", $chord, $i);
                    if($this->is_in($temp, $scale_notes))
                    {
                        $count = sizeof($possible);
                        $possible[$count]["name"] = current($temp);
                        $possible[$count]["type"] = $chord;
                    }
                }
            }
            return $possible;
        }
    }
}
?>