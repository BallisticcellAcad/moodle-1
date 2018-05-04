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

THE_LAYOUT = 'Portuguese(BrazilV4)';

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
        if (ltr.match(/["!@#$%¨&*()_+`{^}|<>:?]/i)) {
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
    if (ltr === 'à') {
        this.shift = true;
        this.alt = false;
        this.accent = true;
    }
    if (ltr === 'À') {
        this.shift = true;
        this.alt = false;
        this.accent = true;
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[ãõ]/)) {
        this.shift = false;
        this.alt = false;
        this.tilde = true;
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[ÃÕ]/)) {
        this.shift = true;
        this.alt = false;
        this.tilde = true;
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[âêô]/i)) {
        this.shift = true;
        this.alt = false;
        this.tilde = true;
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[¹²³£¢¬§//°ªº₢]/i))
    {
        this.shift = false;
        this.alt = true;
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
            document.getElementById('jkeyaltgr').className = "nextSpace";
        }
        if (this.accent) {
            document.getElementById('jkeyacuteaccent').className = "next4";
        }
        if (this.tilde) {
            document.getElementById('jkeytilde').className = "next4";
        }
    };
    this.turnOff = function() {
        if (isLetter(this.chr)) {
            // @codingStandardsIgnoreLine
            if (this.chr.match(/[asdfjklç]/i)) {
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
            document.getElementById('jkeyacuteaccent').className = "normal";
        }
        if (this.tilde) {
            document.getElementById('jkeytilde').className = "normal";
        }
    }
}

/**
 * Set color flag based on current character.
 *
 */
function thenFinger(tCrka) {
    if (tCrka === ' ') {
        return 5; // Highlight the spacebar.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/['"1!¹q/aãáàâ\\|z0)pç;:\-_´`~^/=+§\[{ª\]}º]/i)) {
        return 4; // Highlight the correct key above in red.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[2@²w?sx9(oóôõl.>]/i)) {
        return 3; // Highlight the correct key above in green.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[3#³eéê°dc₢8*iík,<]/i)) {
        return 2; // Highlight the correct key above in yellow.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[4$£rfv5%¢tgb6¨¬yhn7&uújm]/i)) {
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
    } else if (tCrka === "\'" || tCrka === '"') {
        return "jkeycrtica";
    } else if (tCrka === '!' || tCrka === '¹') {
        return "jkey1";
    } else if (tCrka === '@' || tCrka === '²') {
        return "jkey2";
    } else if (tCrka === '#' || tCrka === '³') {
        return "jkey3";
    } else if (tCrka === '$' || tCrka === '£') {
        return "jkey4";
    } else if (tCrka === '%' || tCrka === '¢') {
        return "jkey5";
    } else if (tCrka === '¨' || tCrka === '¬') {
        return "jkey6";
    } else if (tCrka === '&') {
        return "jkey7";
    } else if (tCrka === '*') {
        return "jkey8";
    } else if (tCrka === '(') {
        return "jkey9";
    } else if (tCrka === ')') {
        return "jkey0";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '=' || tCrka === '+' || tCrka === '§') {
        return "jkeyequals";
    } else if (tCrka === '´' || tCrka === '`') {
        return "jkeyacuteaccent";
    } else if (tCrka === '[' || tCrka === '{' || tCrka === 'ª') {
        return "jkeybracketl";
    } else if (tCrka === '~' || tCrka === '^') {
        return "jkeytilde";
    } else if (tCrka === ']' || tCrka === '}' || tCrka === 'º') {
        return "jkeybracketr";
    } else if (tCrka === ';' || tCrka === ':') {
        return "jkeysemicolon";
    } else if (tCrka === "\\" || tCrka === '|') {
        return "jkeybackslash";
    } else if (tCrka === ',' || tCrka === '<') {
        return "jkeycomma";
    } else if (tCrka === '.' || tCrka === '>') {
        return "jkeyperiod";
    } else if (tCrka === 'ã' || tCrka === 'á' || tCrka === 'à' || tCrka === 'â') {
        return "jkeya";
    } else if (tCrka === '?' || tCrka === '/') {
        return "jkeyslash";
    } else if (tCrka === '₢') {
        return "jkeyc";
    } else if (tCrka === 'é' || tCrka === 'ê' || tCrka === '°') {
        return "jkeye";
    } else if (tCrka === 'i' || tCrka === 'í') {
        return "jkeyi";
    } else if (tCrka === 'õ' || tCrka === 'ó' || tCrka === 'ô') {
        return "jkeyo";
    } else if (tCrka === '/') {
        return "jkeyq";
    } else if (tCrka === 'u' || tCrka === 'ú') {
        return "jkeyu";
    } else if (tCrka === 'w' || tCrka === '?') {
        return "jkeyw";
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
    return str.length === 1 && str.match(/[�a-zç]/i);
}
