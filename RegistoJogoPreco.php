<?php

require_once "AmUtil.php";

class RegistoJogoPreco
{
    //data member
    private $mGame;
    private $mOnlineStore;
    private $mPlatform;
    private $mGameVersion;
    private $mCouponText;
    private $mCouponPercentageAndName;
    private $mPriceWithoutCoupon;
    private $mActualPrice;

    public function __construct(
        string $pGame,
        string $pOnlineStore,
        string $pPlatform,
        string $pGameVersion,
        string $pCouponText = null,
        string $pCouponPercentageAndName = null,
        string $pPriceWithoutCoupon = null,
        string $pActualPrice
    )
    {
        $this->mGame = $pGame;
        $this->mOnlineStore = $pOnlineStore;
        $this->mPlatform = $pPlatform;
        $this->mGameVersion = $pGameVersion;
        $this->mCouponText = $pCouponText;
        $this->mCouponPercentageAndName = $pCouponPercentageAndName;
        $this->mPriceWithoutCoupon = $pPriceWithoutCoupon;
        $this->mActualPrice = $pActualPrice;
    }//__construct
}