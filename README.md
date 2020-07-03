# Semi-random, unique music generator using HTML source codes

Re-implementation of my HTML source code based music generator.

Nothing special, just for fun.


(Finding and transposing scales are handled thanks to the music theory library developed by Arturs Sosins.)

--

Demo: https://www.serdarcevher.com/playtheweb

---
## How does it work?

When you submit a web site URL, it:

1. makes a list of used class names in the fetched HTML source code,
2. uses the total length of the code (x) to pick one of the 22 available modes (such as major, lydian etc.): (x mod 21) + 1,
3. uses the length of the #1 popular class name in the code (y) to pick the tone of the piece: (y mod 7) + 1,
4. uses (1 / total letter count of the source code) x 50,000 as the formula to pick the (t).

Once the basic variables are set, it continues by:

5. iterating each class name we had previously listed,
6. using the ASCII value of the first letter of each name (z) in the list as an index to pick a note from the previously picked mode in the 2nd step: (z mod 7) + 1,
8. using the length of each class name (a) to determine the duration of each note: a * t,
9. replacing each note in the array with their respective frequency between 440Hz and 830Hz,
10. adding random stops between iterations: x % 8,

Once the composition is ready, it:

11. plays each frequency for the decided duration using OscillatorNode interface,
12. visualizes each note with a corresponding color and reflecting the duration with a CSS animation.
