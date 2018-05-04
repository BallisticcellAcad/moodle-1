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

THE_LAYOUT = 'Russian(V4)';
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
        // @codingStandardsIgnoreLine
        if (ltr.match(/[!"№;%:?*()_+/,]/i)) {
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
            document.getElementById('jkeyshiftd').className = "next4";
            document.getElementById('jkeyshiftl').className = "next4";
        }
        if ( this.alt) {
            document.getElementById('jkeyaltgr').className = "nextSpace";
        }
    };
    this.turnOff = function() {
        if (isLetter(this.chr)) {
            // @codingStandardsIgnoreLine
            if (this.chr.match(/[фываолдж]/i)) {
                document.getElementById(getKeyID(this.chr)).className = "finger" + thenFinger(this.chr.toLowerCase());
            } else {
                document.getElementById(getKeyID(this.chr)).className = "normal";
            }
        } else {
            document.getElementById(getKeyID(this.chr)).className = "normal";
        }
        if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
            document.getElementById('jkeyenter').classname = "normal";
        }
        if (this.shift) {
            document.getElementById('jkeyshiftd').className = "normal";
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
		// @codingStandardsIgnoreLine
    } else if (tCrka.match(/[ё1!йфя0\-=\\)_+/зхъжэ.,]/i)) {
        return 4; // Highlight the correct key above in red.
    } else if (tCrka.match(/[2цыч"9щдю(]/i)) {
        return 3; // Highlight the correct key above in green.
    } else if (tCrka.match(/[3увс№8шлб*]/i)) {
        return 2; // Highlight the correct key above in yellow.
    } else if (tCrka.match(/[4кам5епи;%6нрт7гоь:?]/i)) {
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
    } else if (tCrka === '\n') {
        return "jkeyenter";
    } else if (tCrka === '!') {
        return "jkey1";
    } else if (tCrka === '"') {
        return "jkey2";
    } else if (tCrka === '№') {
        return "jkey3";
    } else if (tCrka === ';') {
        return "jkey4";
    } else if (tCrka === '%') {
        return "jkey5";
    } else if (tCrka === ':') {
        return "jkey6";
    } else if (tCrka === '?') {
        return "jkey7";
    } else if (tCrka === '*') {
        return "jkey8";
    } else if (tCrka === '(') {
        return "jkey9";
    } else if (tCrka === ')') {
        return "jkey0";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '.' || tCrka === ',') {
        return "jkeyperiod";
    } else if (tCrka === '=' || tCrka === '+') {
        return "jkeyequals";
    } else if (tCrka === "\\" || tCrka === '/') {
        return "jkeybackslash";
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
    return str.length === 1 && str.match(/[ёйцукенгшщзхъфывапролджэячсмитьбю]/i);
}
