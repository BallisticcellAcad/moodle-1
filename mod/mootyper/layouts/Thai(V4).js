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

THE_LAYOUT = 'Thai(V4)';

/**
 * Check for character typed so flags can be set.
 * @param {char} ltr.
 */
function keyboardElement(ltr) {
    this.chr = ltr.toLowerCase();
    this.alt = false;
    // @codingStandardsIgnoreLine
    if (ltr.match(/[ู฿ํ๊็ฺ๋์%+๑๒๓๔๕๖๗๘๙๐"ฎฑธณฯญฐ,ฅฤฆฏโฌษศซ.()ฉฮ?ฒฬฦ]/)) {
        this.shift = true;
    } else {
        this.shift = false;
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
    };
    this.turnOff = function() {
        if (isLetter(this.chr)) {
        // @codingStandardsIgnoreLine
            if (this.chr.match(/[่้็๋ฟหกดเาสวฤฆฏโฌษศซ]/i)) {
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
 * @param {char} tCrka.
 * @returns {number}.
 */
function thenFinger(tCrka) {
    if (tCrka === ' ') {
        return 5; // Highlight the spacebar.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[_%ๅ+ๆ๐ฟฤผ(จ๗ยญวซฝฦข๘บฐง.ช๙ล,ฃฅ]/i)) {
        return 4; // Highlight the correct key above in red.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[/๑ไ"หฆป)ต๖นฯสศใฬ]/i)) {
        return 3; // Highlight the correct key above in green.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[-๒ำฎกฏแฉค๕รณาษมฒ]/i)) {
        return 2; // Highlight the correct key above in yellow.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[ฺุึัี้่ิืูํ้็ื์฿ี๊่๋ท?ภ๓พฑดโอฮถ๔ะธเฌ]/i)) {
        return 1; // Highlight the correct key above in blue.
    } else {
        return 6; // Do not change any highlight.
    }
}

/**
 * Get ID of key to highlight based on current character.
 * @param {char} tCrka.
 * @returns {char}.
 */
function getKeyID(tCrka) {
    if (tCrka === ' ') {
        return "jkeyspace";
    } else if (tCrka === '\n') {
        return "jkeyenter";
    } else if (tCrka === '_' || tCrka === '%') {
        return "jkeybackquote";
    } else if (tCrka === 'ๅ' || tCrka === '+') {
        return "jkey1";
    } else if (tCrka === '/' || tCrka === '๑') {
        return "jkey2";
    } else if (tCrka === '-' || tCrka === '๒') {
        return "jkey3";
    } else if (tCrka === 'ภ' || tCrka === '๓') {
        return "jkey4";
    } else if (tCrka === 'ถ' || tCrka === '๔') {
        return "jkey5";
    } else if (tCrka === 'ุ' || tCrka === 'ู') {
        return "jkey6";
    } else if (tCrka === 'ึ' || tCrka === '฿') {
        return "jkey7";
    } else if (tCrka === 'ค' || tCrka === '๕') {
        return "jkey8";
    } else if (tCrka === 'ต' || tCrka === '๖') {
        return "jkey9";
    } else if (tCrka === 'จ' || tCrka === '๗') {
        return "jkey0";
    } else if (tCrka === 'ข' || tCrka === '๘') {
        return "jkeyminus";
    } else if (tCrka === 'ช' || tCrka === '๙') {
        return "jkeyequals";
    } else if (tCrka === 'ๆ' || tCrka === '๐') {
        return "jkeyq";
    } else if (tCrka === 'ไ' || tCrka === '"') {
        return "jkeyw";
    } else if (tCrka === 'ำ' || tCrka === 'ฎ') {
        return "jkeye";
    } else if (tCrka === 'พ' || tCrka === 'ฑ') {
        return "jkeyr";
    } else if (tCrka === 'ะ' || tCrka === 'ธ') {
        return "jkeyt";
    } else if (tCrka === 'ั' || tCrka === 'ํ') {
        return "jkeyy";
    } else if (tCrka === 'ี' || tCrka === '๊') {
        return "jkeyu";
    } else if (tCrka === 'ร' || tCrka === 'ณ') {
        return "jkeyi";
    } else if (tCrka === 'น' || tCrka === 'ฯ') {
        return "jkeyo";
    } else if (tCrka === 'ย' || tCrka === 'ญ') {
        return "jkeyp";
    } else if (tCrka === 'บ' || tCrka === 'ฐ') {
        return "jkeybracketl";
    } else if (tCrka === 'ล' || tCrka === ',') {
        return "jkeybracketr";
    } else if (tCrka === 'ฃ' || tCrka === 'ฅ') {
        return "jkeybackslash";
    } else if (tCrka === 'ฟ' || tCrka === 'ฤ') {
        return "jkeya";
    } else if (tCrka === 'ห' || tCrka === 'ฆ') {
        return "jkeys";
    } else if (tCrka === 'ก' || tCrka === 'ฏ') {
        return "jkeyd";
    } else if (tCrka === 'ด' || tCrka === 'โ') {
        return "jkeyf";
    } else if (tCrka === 'เ' || tCrka === 'ฌ') {
        return "jkeyg";
    } else if (tCrka === '้' || tCrka === '็') {
        return "jkeyh";
    } else if (tCrka === '่' || tCrka === '๋') {
        return "jkeyj";
    } else if (tCrka === 'า' || tCrka === 'ษ') {
        return "jkeyk";
    } else if (tCrka === 'ส' || tCrka === 'ศ') {
        return "jkeyl";
    } else if (tCrka === 'ว' || tCrka === 'ซ') {
        return "jkeysemicolon";
    } else if (tCrka === 'ง' || tCrka === '.') {
        return "jkeyapostrophe";
    } else if (tCrka === 'ผ' || tCrka === '(') {
        return "jkeyz";
    } else if (tCrka === 'ป' || tCrka === ')') {
        return "jkeyx";
    } else if (tCrka === 'แ' || tCrka === 'ฉ') {
        return "jkeyc";
    } else if (tCrka === 'อ' || tCrka === 'ฮ') {
        return "jkeyv";
    } else if (tCrka === 'ิ' || tCrka === 'ฺ') {
        return "jkeyb";
    } else if (tCrka === 'ื' || tCrka === '์') {
        return "jkeyn";
    } else if (tCrka === 'ท' || tCrka === '?') {
        return "jkeym";
    } else if (tCrka === 'ม' || tCrka === 'ฒ') {
        return "jkeycomma";
    } else if (tCrka === 'ใ' || tCrka === 'ฬ') {
        return "jkeyperiod";
    } else if (tCrka === 'ฝ' || tCrka === 'ฦ') {
        return "jkeyslash";
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
    return str.length === 1 && str.match(/[ก-ฮ]/i);
}
