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
    keyPressed,
    event;

/**
 * Check for combined character.
 * @param {char} chr.
 * @returns {char}.
 */
function isCombined(chr) {
    return (chr === '´' || chr === '`' || chr === '~');
}

THE_LAYOUT = 'Belgium(DutchV4)';

/**
 * Process keyup for combined character.
 * @param {char} e.
 * @returns {bolean}.
 */
function keyupCombined(e) {
    if (ended) {
        return false;
    }
    if (!started) {
        doStart();
    }
    var keychar = getPressedChar(e);
    if (keychar === '[not_yet_defined]') {
        combinedChar = true;
        return true;
    }
    if (combinedCharWait) {
        combinedCharWait = false;
        return true;
    }
    var currentText = $('#tb1').val();
    var lastChar = currentText.substring(currentText.length - 1);
    if (combinedChar && lastChar === currentChar) {
        if (show_keyboard) {
            var thisE = new keyboardElement(currentChar);
            thisE.turnOff();
        }
        if (currentPos === fullText.length - 1) {   // END.
            doKonec();
            return true;
        }
        if (currentPos < fullText.length - 1) {
            var nextChar = fullText[currentPos + 1];
            if (show_keyboard){
                var nextE = new keyboardElement(nextChar);
                nextE.turnOn();
            }
            if (!isCombined(nextChar)) {            // If next char is not combined char.
                $("#form1").off("keyup", "#tb1");
                $("#form1").on("keypress", "#tb1", keyPressed);
            }
        }
        combinedChar = false;
        moveCursor(currentPos + 1);
        currentChar = fullText[currentPos + 1];
        currentPos++;
        return true;
    } else {
        combinedChar = false;
        napake++;
        var tbval = $('#tb1').val();
        $('#tb1').val(tbval.substring(0, currentPos));
        return false;
    }
}

/**
 * Process keyupFirst.
 * @param {char} event.
 * @returns {bolean}.
 */
function keyupFirst(event) {
    $("#form1").off("keyup", "#tb1", keyupFirst);
    $("#form1").on("keyup", "#tb1", keyupCombined);
    return false;
}

/**
 * Check for character typed so flags can be set.
 * @param {char} ltr.
 */
function keyboardElement(ltr) {
    this.chr = ltr.toLowerCase();
    this.alt = false;
    this.accent = false;
    if (isLetter(ltr)) {
        this.shift = ltr.toUpperCase() === ltr;
    } else {
        // @codingStandardsIgnoreLine
        if (ltr.match(/[³1234567890°_¨*%£>?./+]/i)) {
            this.shift = true;
        } else {
            this.shift = false;
        }
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[\\|@#€{}\[\]~´`ñ]/i)) {
        this.shift = false;
        this.alt = true;
        this.accent = false;
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[ëïöü]/i)) {
        this.shift = true;
        this.alt = false;
        this.accent = false;
        this.caret = true;
    }
    if (ltr === 'ê') {
        this.shift = false;
        this.alt = false;
        this.accent = false;
        this.caret = true;
    }
    if (ltr === 'ó' || ltr === 'á') {
        this.shift = false;
        this.alt = true;
        this.accent = true;
    }
    if (ltr === 'ñ') {
        this.shift = false;
        this.alt = true;
        this.accent = false;
        this.tilde = true;
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
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "next2";
        }
        if (this.accent) {
            document.getElementById('jkeyù').className = "next4";
        }
        if (this.caret) {
            document.getElementById('jkeycaret').className = "next4";
        }
        if (this.tilde) {
            document.getElementById('jkeyequal').className = "next4";
        }
    };
    this.turnOff = function() {
        if (isLetter(this.chr)) {
            // @codingStandardsIgnoreLine
            if (this.chr.match(/[qsdfjklm]/i)) {
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
        if (this.accent) {
            document.getElementById('jkeyù').className = "normal";
        }
        if (this.caret) {
            document.getElementById('jkeycaret').className = "normal";
        }
        if (this.tilde) {
            document.getElementById('jkeyequal').className = "normal";
        }
    };
}

/**
 * Set color flag based on current character.
 * @param {char} tCrka.
 * @returns {int}.
 */
function thenFinger(tCrka) {
    if (tCrka === ' ') {
        return 5; // Highlight the spacebar.
        // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[²³&1|aáqw<>\\à0}pm)°^¨\[ù%´=+~\-_$*\]µ£`]/i)) {
        return 4; // Highlight the correct key above in red.
        // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[é2@zsxç9{oóöl:/]/i)) {
        return 3; // Highlight the correct key above in green.
        // @codingStandardsIgnoreLine
    } else if (tCrka.match(/["3#eéë€êdc!8iíïk;.]/i)) {
        return 2; // Highlight the correct key above in yellow.
        // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[\'4rf(5tgbv§6yhnñè7uúüj,?]/i)) {
        return 1; // Highlight the correct key above in blue.
    } else {
        return 6;
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
    } else if (tCrka === ',' || tCrka === '?') {
        return "jkeycomma";
    } else if (tCrka === ';' || tCrka === '.') {
        return "jkeysemicolon";
    } else if (tCrka === ':' || tCrka === '/') {
        return "jkeycolon";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '=' || tCrka === '+' || tCrka === '~') {
        return "jkeyequal";
    } else if (tCrka === '&' || tCrka === '|') {
        return "jkey1";
    } else if (tCrka === 'é' || tCrka === '@') {
        return "jkey2";
    } else if (tCrka === '"' || tCrka === '#') {
        return "jkey3";
    } else if (tCrka === '\'') {
        return "jkey4";
    } else if (tCrka === '(') {
        return "jkey5";
    } else if (tCrka === '§') {
        return "jkey6";
    } else if (tCrka === 'è') {
        return "jkey7";
    } else if (tCrka === '!') {
        return "jkey8";
    } else if (tCrka === 'ç' || tCrka === '{') {
        return "jkey9";
    } else if (tCrka === 'à' || tCrka === '}') {
        return "jkey0";
    } else if (tCrka === '^' || tCrka === '¨' || tCrka === '[') {
        return "jkeycaret";
    } else if (tCrka === '$' || tCrka === '*' || tCrka === ']') {
        return "jkeydollar";
    } else if (tCrka === '%' || tCrka === '´') {
        return "jkeyù";
    } else if (tCrka === '£' || tCrka === '`') {
        return "jkeyµ";
    } else if (tCrka === ')' || tCrka === '°') {
        return "jkeyparenr";
    } else if (tCrka === '<' || tCrka === '>') {
        return "jkeyckck";
    } else if (tCrka === '²' || tCrka === '³') {
        return "jkeytildo";
    } else if (tCrka === 'a' || tCrka === 'á') {
        return "jkeya";
    } else if (tCrka === '¨') {
        return "jkeycaret";
    } else if (tCrka === '€' || tCrka === 'é' || tCrka === 'ë' || tCrka === 'ê') {
        return "jkeye";
    } else if (tCrka === 'i' || tCrka === 'í' || tCrka === 'ï') {
        return "jkeyi";
    } else if (tCrka === 'ñ') {
        return "jkeyn";
    } else if (tCrka === 'o' || tCrka === 'ó' || tCrka === 'ö') {
        return "jkeyo";
    } else if (tCrka === 'u' || tCrka === 'ú' || tCrka === 'ü') {
        return "jkeyu";
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
    return str.length === 1 && str.match(/[0-9a-z¡ñçáéíóúüùµ]/i);
}
