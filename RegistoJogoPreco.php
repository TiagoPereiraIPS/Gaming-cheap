<?php

require_once "AmUtil.php";

class RegistoJogoPreco
{
    //data member
    public $mGame;
    public $mOnlineStore;
    public $mPlatform;
    public $mGameVersion;
    public $mCouponPercentageAndName;
    public $mPriceWithoutCoupon;
    public $mActualPrice;

    public function __construct(
        string $pGame,
        string $pOnlineStore,
        string $pPlatform,
        string $pGameVersion,
        string $pCouponPercentageAndName = null,
        string $pPriceWithoutCoupon = null,
        string $pActualPrice
    )
    {
        $this->mGame = $pGame;
        $this->mOnlineStore = $pOnlineStore;
        $this->mPlatform = $pPlatform;
        $this->mGameVersion = $pGameVersion;
        $this->mCouponPercentageAndName = $pCouponPercentageAndName;
        $this->mPriceWithoutCoupon = $pPriceWithoutCoupon;
        $this->mActualPrice = $pActualPrice;
    }//__construct
}