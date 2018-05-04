var THE_LAYOUT,
    ended = false,
    started = false,
    doStart,
    getPressedChar,
    combinedChar,
    combinedCharWait,
    $,
    currentChar,
    show_keyboard,
    currentPos,
    fullText,
    doKonec,
    moveCursor,
    napake,
    keyupFirst,
    event;

/**
 * Check for combined character.
 * @param {char} chr.
 * @returns {char}.
 */
function isCombined(chr) {
    return false;
}

/**
 * Process keyup for combined character.
 * @param {char} e.
 * @returns {bolean}.
 */
function keyupCombined(e) {
    return false;
}

/**
 * Process keyupFirst.
 * @param {char} event.
 * @returns {bolean}.
 */
function keyupFirst(event) {
    return false;
}

THE_LAYOUT = 'Japanese(V4)';

/**
 * Check for character typed so flags can be set.
 * @param {char} ltr.
 */
function keyboardElement(ltr) {
    this.chr = ltr.toLowerCase();
    this.alt = false;
    if (isLetter(ltr)) {
        this.shift = ltr.toUpperCase() === ltr;
    } else {
        if (ltr === '!' || ltr === '"' || ltr === '#' || ltr === '$' || ltr === '%' || ltr === '&' ||
            ltr === '\'' || ltr === '(' || ltr === ')' || ltr === '' || ltr === '=' || ltr === '~' || ltr === '|' ||
            ltr === '`' || ltr === '{' || ltr === '+' || ltr === '*' || ltr === '}' ||
            ltr === '<' || ltr === '>' || ltr === '?' || ltr === '_') {
            this.shift = true;
        } else {
            this.shift = false;
        }
    }
    this.turnOn = function() {
        if (isLetter(this.chr)) {
            document.getElementById(getKeyID(this.chr)).className = "next" + thenFinger(this.chr.toLowerCase());
        } else if (this.chr === ' ') {
            document.getElementById(getKeyID(this.chr)).className = "nextSpace";
        } else {
            document.getElementById(getKeyID(this.chr)).className = "next" + thenFinger(this.chr.toLowerCase());
        }
        if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
            document.getElementById('jkeyenter').className = "next4";
        }
        if (this.shift) {
            document.getElementById('jkeyshiftr').className = "next4";
            document.getElementById('jkeyshiftl').className = "next4";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "nextSpace";
        }
    };
    this.turnOff = function() {
        if (isLetter(this.chr)) {
            if (this.chr === 'a' || this.chr === 's' || this.chr === 'd' || this.chr === 'f' ||
               this.chr === 'j' || this.chr === 'k' || this.chr === 'l') {
                document.getElementById(getKeyID(this.chr)).className = "finger" + thenFinger(this.chr.toLowerCase());
            } else {
                document.getElementById(getKeyID(this.chr)).className = "normal";
            }
        } else if (this.chr === ':' || this.chr === ';') {    // English specific ; and :.
            document.getElementById(getKeyID(this.chr)).className = "finger4";
        } else {
            document.getElementById(getKeyID(this.chr)).className = "normal";
        }
        if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
            document.getElementById('jkeyenter').classname = "normal";
        }
        if (this.shift) {
            document.getElementById('jkeyshiftr').className = "normal";
            document.getElementById('jkeyshiftl').className = "normal";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "normal";
        }
    };
}
/**
 * Set color flag based on current character.
 *
 */
function thenFinger(tCrka) {
    if (tCrka === ' ') {
        return 5; // Highlight the spacebar.
    } else if (tCrka === '1' || tCrka === 'q' || tCrka === 'a' || tCrka === 'z' ||
            tCrka === '!' ||
            tCrka === '0' || tCrka === 'p' || tCrka === ';' || tCrka === '/' ||
            tCrka === '+' || tCrka === '?' ||
            tCrka === '-' || tCrka === '@' || tCrka === ':' || tCrka === '\\' ||
            tCrka === '=' || tCrka === '`' || tCrka === '*' || tCrka === '_' ||
            tCrka === '^' || tCrka === '[' || tCrka === ']' ||
            tCrka === '~' || tCrka === '{' || tCrka === '}' ||
            tCrka === '\\' || tCrka === '|') {
        return 4; // Highlight the correct key above in red.
    } else if (tCrka === '2' || tCrka === 'w' || tCrka === 's' || tCrka === 'x' ||
            tCrka === '"' ||
            tCrka === '9' || tCrka === 'o' || tCrka === 'l' || tCrka === '.' ||
            tCrka === ')' || tCrka === '>') {
        return 3; // Highlight the correct key above in green.
    } else if (tCrka === '3' || tCrka === 'e' || tCrka === 'd' || tCrka === 'c' ||
          tCrka === '#' ||
          tCrka === '8' || tCrka === 'i' || tCrka === 'k' || tCrka === ',' ||
          tCrka === '(' || tCrka === '<' ) {
        return 2; // Highlight the correct key above in yellow.
    } else if (tCrka === '4' || tCrka === 'r' || tCrka === 'f' || tCrka === 'v' ||
          tCrka === '$' ||
          tCrka === '5' || tCrka === 't' || tCrka === 'g' || tCrka === 'b' ||
          tCrka === '%' ||
          tCrka === '6' || tCrka === 'y' || tCrka === 'h' || tCrka === 'n' ||
          tCrka === '&' ||
          tCrka === '7' || tCrka === 'u' || tCrka === 'j' || tCrka === 'm' ||
          tCrka === '\'') {
        return 1; // Highlight the correct key above in blue.
    } else {
        return 6; // Do not change any highlight.
    }
}

/**
 * Get ID of key to highlight based on current character.
 * @param {char} tCrka.
 * @returns {varchar}.
 */
function getKeyID(tCrka) {
    if (tCrka === ' ') {
        return "jkeyspace";
    } else if (tCrka === '!') {
        return "jkey1";
    } else if (tCrka === '"') {
        return "jkey2";
    } else if (tCrka === '#') {
        return "jkey3";
    } else if (tCrka === '$') {
        return "jkey4";
    } else if (tCrka === '%') {
        return "jkey5";
    } else if (tCrka === '&') {
        return "jkey6";
    } else if (tCrka === '\'') {
        return "jkey7";
    } else if (tCrka === '(') {
        return "jkey8";
    } else if (tCrka === ')') {
        return "jkey9";
    } else if (tCrka === '-') {
        return "jkeyminus";
    } else if (tCrka === '^' || tCrka === '~') {
        return "jkeycaret";
    } else if (tCrka === '|') {
        return "jkeyyen";
    } else if (tCrka === '@' || tCrka === '`') {
        return "jkeyat";
    } else if (tCrka === '[' || tCrka === '{') {
        return "jkeybracketopen";
    } else if (tCrka === ';' || tCrka === '+') {
        return "jkeysemicolon";
    } else if (tCrka === ':' || tCrka === '*') {
        return "jkeypodpicje";
    } else if (tCrka === ']' || tCrka === '}') {
        return "jkeybracketclose";
    } else if (tCrka === ',' || tCrka === '<') {
        return "jkeycomma";
    } else if (tCrka === '.' || tCrka === '>') {
        return "jkeyperiod";
    } else if (tCrka === '/' || tCrka === '?') {
        return "jkeyslash";
    } else if (tCrka === '\\' || tCrka === '_') {
        return "jkeybackslash";
    } else if (tCrka === '\n') {
        return "jkeyenter";
    } else {
        return "jkey" + tCrka;
    }
}

/**
 * Is the typed letter part of the current alphabet.
 * @param {char} str.
 * @returns {(int|Array)}.
 */
function isLetter(str) {
    return str.length === 1 && str.match(/[a-z]/i);
}
