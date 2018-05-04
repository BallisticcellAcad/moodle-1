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

THE_LAYOUT = 'Spanish(V4)';

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
            doTheEnd();
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
        mistake++;
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
        this.shift = ltr.toUpperCase() === ltr && ltr != '¡' && ltr != '`';
    } else {
        // @codingStandardsIgnoreLine
        if (ltr.match(/[ª<!"·$%&/()=?¿^*¨;:_]/i)) {
            this.shift = true;
        } else {
            this.shift = false;
        }
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[áéíóú]/)) {
        this.shift = false;
        this.alt = false;
        this.accent = true;
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[ÁÉÍÓÚ]/)) {
        this.shift = true;
        this.alt = false;
        this.accent = true;
    }
    if (ltr === 'ü') {
        this.shift = true;
        this.alt = false;
        this.accent = true;
    }
    if (ltr === 'Ü') {
        this.shift = true;
        this.alt = false;
        this.accent = true;
    }
		// @codingStandardsIgnoreLine
        if (ltr.match(/[\\|@#~€¬\[\]{}]/i)) {
        this.shift = false;
        this.alt = true;
        this.accent = false;
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
            document.getElementById('jkeyrighttick').className = "next4";
        }
    };
    this.turnOff = function() {
        if (isLetter(this.chr)) {
        // @codingStandardsIgnoreLine
            if (this.chr.match(/[asdfjklñ]/i)) {
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
            document.getElementById('jkeyrighttick').className = "normal";
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
    } else if (tCrka.match(/[ºª\\1!|qaáz<>0=pñ\'?`^\[´¨{\-_¡¿+*\]ç}]/i)) {
        return 4; // Highlight the correct key above in red.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[2"@wsx9)oól.:]/i)) {
        return 3; // Highlight the correct key above in green.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[3·#eé€dc8(iík,;]/i)) {
        return 2; // Highlight the correct key above in yellow.    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[4$~rf5%€tgv6&¬yhnb7/uúüjm]/i)) {
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
    } else if (tCrka === ',' || tCrka === ';') {
        return "jkeycomma";
    } else if (tCrka === '\n') {
        return "jkeyenter";
    } else if (tCrka === '.' || tCrka === ':') {
        return "jkeyperiod";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '!' || tCrka === '|') {
        return "jkey1";
    } else if (tCrka === '"' || tCrka === '@') {
        return "jkey2";
    } else if (tCrka === '·' || tCrka === '#') {
        return "jkey3";
    } else if (tCrka === '$'|| tCrka === '~') {
        return "jkey4";
    } else if (tCrka === '%') {
        return "jkey5";
    } else if (tCrka === '&' || tCrka === '¬') {
        return "jkey6";
    } else if (tCrka === '/') {
        return "jkey7";
    } else if (tCrka === '(') {
        return "jkey8";
    } else if (tCrka === ')') {
        return "jkey9";
    } else if (tCrka === '=') {
        return "jkey0";
    } else if (tCrka === '`' || tCrka === '^' || tCrka === '[') {
        return "jkeylefttick";
    } else if (tCrka === '´' || tCrka === '¨' || tCrka === '{') {
        return "jkeyrighttick";
    } else if (tCrka === 'ç' || tCrka === '}') {
        return "jkeyç";
    } else if (tCrka === "'" || tCrka === '?') {
        return "jkeyapostrophe";
    } else if (tCrka === '*' || tCrka === '+' || tCrka === ']') {
        return "jkeyplus";
    } else if (tCrka === '<' || tCrka === '>') {
        return "jkeyckck";
    } else if (tCrka === 'º' || tCrka === 'ª' || tCrka === '\\') {
        return "jkeytilde";
    } else if (tCrka === '¿') {
        return 'jkey¡';
    } else if (tCrka === 'a' || tCrka === 'á') {
        return "jkeya";
    } else if (tCrka === 'e' || tCrka === 'é' || tCrka === '€') {
        return "jkeye";
    } else if (tCrka === 'i' || tCrka === 'í') {
        return "jkeyi";
    } else if (tCrka === 'o' || tCrka === 'ó') {
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
    return str.length === 1 && str.match(/[a-z`¡ñçáéíóúü]/i);
}
