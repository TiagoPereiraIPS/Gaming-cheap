<?php

require_once "RegistoJogoPreco.php";

class AmUtil{
    const IMPOSSIBLE_MONTH = -1;
    const BOT_SIGNATURE = "For educational tests only";

    public static function leapYear(
        $pY
    ){
        return ($pY%400 === 0) || ($pY%4===0 && ($pY%100!==0));
    }//leapYear

    public static function numberOfDaysInMonth(
        $pY,
        $pM
    ){
        switch($pM){
            case 1: case 3:case 5:case 7:case 8: case 10;case 12: return 31;
            case 4: case 6:case 9:case 11: return 30;
            case 2: return (self::leapYear($pY) ? 29 :  28);
            default: return self::IMPOSSIBLE_MONTH;
        }//switch
    }//numberOfDaysInMonth

    public static function consumeUrl(
        $pUrl //can be an HTML page, can be a JPG, ...
    ){
        //$bValid = is_string($pUrl) && strlen($pUrl);
        $ch = curl_init($pUrl);
        if ($ch){
            //curl_setopt(CURLOPT_URL, $pUrl);
            /*
             * makes it explic that the request
             * will happen using HTTP GET
             */
            curl_setopt(
                $ch,
                CURLOPT_HTTPGET,
                true
            );

            /*
             * disables the verification of SSL
             * certificates
             * useful when not using cacert.pem
             */
            curl_setopt(
                $ch,
                CURLOPT_SSL_VERIFYPEER,
                false
            );

            /*
             * sets a user agent string for our
             * software
             */
            curl_setopt(
                $ch,
                CURLOPT_USERAGENT,
                self::BOT_SIGNATURE
            );

            //if set to true, curl_exec will return
            //the data consumed at the URL
            //instead of just true/false
            curl_setopt(
                $ch,
                CURLOPT_RETURNTRANSFER,
                true
            );

            /*
             * makes it clear that we want all the bytes
             */
            curl_setopt(
                $ch,
                CURLOPT_BINARYTRANSFER, //deprecated
                true
            );

            /*
             * sets automatic handling of the encoded
             * data
             */
            curl_setopt(
                $ch,
                CURLOPT_ENCODING,
                ""
            );

            $bin = curl_exec($ch);

            return $bin;
        }//if
        return false;
    }//consumeUrl

    public static function extractFirstTenResultsOfGamePrices(
        string $pStrHtmlSourceCode,
        string $pClassName,
        string $pGameName
    ) /*: array */
    {
        $aCount = 0;
        $aRet = []; //the collection of all "div" elements found
        $oDom = new DOMDocument();
        if ($oDom){
            //@ - "silencer"
            @$oDom->loadHTML($pStrHtmlSourceCode);
            /*
             * array of "a" elements
             */
            $as = $oDom->getElementsByTagName('div');

            foreach ($as as $someAElement){
                $strClass = trim($someAElement->getAttribute('class'));

                if ($strClass === $pClassName && $aCount<10) {
                    $aCount++;
                    if (strpos(str_replace(' ', '', $someAElement->childNodes[7]->childNodes[1]->textContent), 'Pricewithcoupon')) {
                        $oGameRegister = new RegistoJogoPreco(
                            $pGameName,
                            trim($someAElement->childNodes[1]->childNodes[1]->textContent),
                            trim($someAElement->childNodes[3]->textContent),
                            trim($someAElement->childNodes[5]->textContent),
                            trim($someAElement->childNodes[7]->childNodes[1]->childNodes[3]->textContent),
                            trim($someAElement->childNodes[7]->childNodes[3]->childNodes[1]->textContent),
                            trim($someAElement->childNodes[7]->childNodes[3]->childNodes[3]->textContent)
                        );
                    } else {
                        $oGameRegister = new RegistoJogoPreco(
                            $pGameName,
                            trim($someAElement->childNodes[1]->childNodes[1]->textContent),
                            trim($someAElement->childNodes[3]->textContent),
                            trim($someAElement->childNodes[5]->textContent),
                            null,
                            null,
                            trim($someAElement->childNodes[7]->childNodes[1]->textContent)
                        );
                    }
                    $aRet[] = $oGameRegister;
                }
            }//foreach
        }//if
        return $aRet;
    }//extractFirstTenResultsOfSpecificClassType

    /*
     * receives HTML source code
     * returns a collection of all "a" elements found,
     * structured as pairs "anchor", "href"
     *
     * E.g.
     * if this is the input:
     * <html><body><a href="URL1">anchor1</a></body></html>"
     * the output should be:
     * [
     *  [ "anchor" => "anchor1", "href" => "URL1"]
     * ]
     */
    const KEY_HREF = "HREF";
    const KEY_ANCHOR = "ANCHOR";
    public static function extractHyperlinksFromHtmlSourceCode(
        string $pStrHtmlSourceCode
    ) /*: array */
    {
        $aRet = []; //the collection of all "a" elements found
        $oDom = new DOMDocument();
        if ($oDom){
            //@ - "silencer"
            @$oDom->loadHTML($pStrHtmlSourceCode);
            /*
             * array of "a" elements
             */
            $as = $oDom->getElementsByTagName('a');

            //foreach ($col as $indexOfElement => $valueOfElement){body}
            //foreach ($col as $valueOfElement){body}
            foreach ($as as $someAElement){
                $strAnchor = trim($someAElement->nodeValue);
                $strHref = trim($someAElement->getAttribute('href'));

                $aPair = [
                    self::KEY_HREF => $strHref,
                    self::KEY_ANCHOR => $strAnchor
                ];

                $aRet[] = $aPair;
            }//foreach
        }//if
        return $aRet;
    }//extractHyperlinksFromHtmlSourceCode

    //**
    /*
     * tool to filter Hyperlinks,
     * keeping only those with certain href endings
     * e.g.
     * input [
         * ["anchor"=>?, "href"=>".xpto"],
         * ["anchor"=>"pic", "href"=>"bla.jpg"]
     * ]
     *
     *
     */
    const IMAGE_FILTERS = [
        ".jpg", ".png", ".jp2", ".gif",
        ".gifv", ".bmp", ".svg"
    ];
    public static function
filterHyperlinksKeepingOnlyThoseWithHrefsEndingIn(
        $paHyperlinksAsPairsAnchorsHref,
        $paFilters = [], //no filters, by default!
        $pStrURLPrefixIfSchemaIsMissing = "https:"
    )
    {
        $aRet = [];
        $hrefs = []; //2020-12-03

        $bShouldDoNothing =
            is_array($paFilters) && count($paFilters)===0;

        if ($bShouldDoNothing)
            return $paHyperlinksAsPairsAnchorsHref;

        //if there are filters
        foreach (
            $paHyperlinksAsPairsAnchorsHref
            as
            $aPair
        ){
            $strAnchor = $aPair[self::KEY_ANCHOR];
            $strHref = $aPair[self::KEY_HREF];

            $bHrefEndsInAtLeastOneOfTheFilters =
                self::stringEndsInOneOfTheFollowing(
                    $strHref,
                    $paFilters
                );

            if ($bHrefEndsInAtLeastOneOfTheFilters){
                $bUrlIsMissingSchema = stripos(
                    $strHref, "//"
                ) === 0;
                if ($bUrlIsMissingSchema){
                    $strHref =
                        "$pStrURLPrefixIfSchemaIsMissing$strHref";

                    $aPair[self::KEY_HREF] = $strHref;
                }

                $bHrefFoundAlreadyExistsInCollectionOfHrefs =
                    array_search(
                        $strHref,
                        $hrefs
                    ) !== false;

                $bNewHref = !$bHrefFoundAlreadyExistsInCollectionOfHrefs;
                if ($bNewHref){
                    $hrefs[] = $strHref; //conditional entry (depends on being new)
                    $aRet[] = $aPair; //conditional entry (depends on being new)
                }//if
                else{
                    //feedback for repeat rejects
                }
            }//if
        }//foreach

        return $aRet;
    }//filterHyperlinksKeepingOnlyThoseWithHrefsEndingIn

    /*
     * stringEndsInOneOfTheFollowing ("Artur", ["ab", "r"]) => true
     * stringEndsInOneOfTheFollowing ("pic.png", [".png", "jpg"]) => true
     * case INSENSITIVE!
     */
    public static function stringEndsInOneOfTheFollowing(
        string $pStr,
        array $paTerminations,
        bool $pbCaseInsensitive = true
    ){
        foreach($paTerminations as $someTermination){
            if ($pbCaseInsensitive){
                $iWhereDoesTheTerminationOccur =
                    strripos($pStr, $someTermination);
            }//if
            else{
                $iWhereDoesTheTerminationOccur =
                    strrpos($pStr, $someTermination);
            }//else

            $bTerminationOccurs =
                $iWhereDoesTheTerminationOccur!==false;

            if ($bTerminationOccurs){
                //it it exactly at the END of the string?
                $bExactlyAtTheEnd =
                    strlen($pStr) ===
                        $iWhereDoesTheTerminationOccur +
                        strlen($someTermination);
                if ($bExactlyAtTheEnd) return true;
            }//if
        }//foreach
        return false;
    }//stringEndsInOneOfTheFollowing

    const INVALID_FILENAME_SYMBOLS =
        [":", "/", "\\", "*", "?", "<", ">", "|", "\""];

    public static function sanitizeStringForFileSystem(
        $pStrToSanitize,
        $pStrReplacementSymbol = "_"
    ){
        foreach (self::INVALID_FILENAME_SYMBOLS as $strReplaceThis){
            $pStrToSanitize = str_replace(
                $strReplaceThis,
                $pStrReplacementSymbol,
                $pStrToSanitize
            );
        }//foreach

        return $pStrToSanitize;
    }//sanitizeStringForFileSystem
}//AmUtil