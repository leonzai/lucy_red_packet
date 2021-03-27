<?php


class LuckRedPacked
{

    /**
     * 红包金额
     *
     * @var float
     */
    protected $totalMoney;

    /**
     * 红包个数
     *
     * @var int
     */
    protected $numberOfRedPacket;

    /**
     * 指定位置
     *
     * @var int
     */
    protected $specifyIndex;

    /**
     * 指定金额
     *
     * @var int
     */
    protected $specifyMoney;


    // 剩余多少钱
    protected $spareMoney;


    public function __construct($amount, $num, $specifyIndex = -1, $specifyMoney = -1)
    {
        $this->spareMoney = $amount;
        $this->totalMoney = $amount;
        $this->numberOfRedPacket = $num;
        $this->specifyIndex = $specifyIndex;
        $this->specifyMoney = $specifyMoney;
    }

    public function handle()
    {
        echo "<h4>测试数据：</h4>";
        for ($i = 0; $i < $this->numberOfRedPacket; $i++) {
            $index = $i + 1;
            echo "第 $index 个： ";
            echo $this->lucky($i) / 100 . " 元<br>";
        }
        echo "===========================================================================<br>";
    }

    public function lucky($i)
    {
        // 如果是指定的人，直接返回指定的金额
        if ($i === $this->specifyIndex) {
            $this->spareMoney -= $this->specifyMoney;
            return $this->specifyMoney;
        }

        // 如果是最后一个，直接返回剩余金额
        if ($i === $this->numberOfRedPacket - 1) return $this->spareMoney;


        // 剩下几个红包
        $spareNumberOfRedPacket = $this->numberOfRedPacket - $i;


        if ($i > $this->specifyIndex) {
            $lucyMaxMoney = $this->spareMoney;  // 计算剩余多少钱可以发放到当前用户。
            $maxMoney = $lucyMaxMoney / $spareNumberOfRedPacket * 2; // 腾讯算法，用户最多中多少钱
        } else {
            $lucyMaxMoney = $this->spareMoney - $this->specifyMoney;
            $maxMoney = $lucyMaxMoney / ($spareNumberOfRedPacket - 1) * 2; // 腾讯算法，用户最多中多少钱
        }

        // maxMoney 可能是小数
        $maxMoney = floor($maxMoney);

        // 最少剩下要多少钱，才能保证其他人（不包含当前用户）都能抽到钱
        if ($i > $this->specifyIndex) {
            $minMoney = ($spareNumberOfRedPacket - 1) * 1;  // *1 表示一人一分钱   -1 是减去当前用户
        } else {
            $minMoney = ($spareNumberOfRedPacket - 1 - 1) * 1 + $this->specifyMoney; // -1 -1 是减去当前用户和指定用户
        }

        if ($maxMoney > $this->spareMoney - $minMoney) {
            $maxMoney = $this->spareMoney - $minMoney;
        }

        $lucyMoney = rand(1, $maxMoney);
        $this->spareMoney -= $lucyMoney;
        return $lucyMoney;
    }

}

function go()
{
    // 红包金额验证，最终转化成 分
    if (empty($_REQUEST['money'])) return "红包金额不能为空";
    if (!is_numeric($_REQUEST['money'])) return "红包金额格式不正确";
    $money = intval($_REQUEST['money'] * 100);
    if ($money < 1) return "红包金额必须大于 0";

    // 红包数量验证
    if (empty($_REQUEST['num'])) return "红包数量不能为空";
    $num = intval($_REQUEST['num']);
    if ($num < 1) return "红包数量应为正整数";

    if ($num > $money * 100) return "每个红包的金额不得低于一分钱";

    // 指定位置验证，最终还要转成索引值，也就是 -1
    $specifyIndex = -1;
    if (!empty($_REQUEST['position'])) {
        $tmpSpecifyIndex = intval($_REQUEST['position']);
        if ($tmpSpecifyIndex < 1) return "命中位置应为正整数";
        if ($num < $tmpSpecifyIndex) return "命中位置不能大于红包数量";
        $specifyIndex = $tmpSpecifyIndex - 1; // 位置 - 索引值 = 1
    }

    // 指定金额验证，最终转化成 分
    $specifyMoney = -1;
    if (!empty($_REQUEST['amount'])) {
        if (!is_numeric($_REQUEST['amount'])) return "命中金额格式不正确";
        $specifyMoney = intval($_REQUEST['amount'] * 100);
        if ($specifyMoney < 1) return "命中金额必须大于 0";
        if ($specifyMoney > ($money - $num + 1)) return "根据红包数量和红包金额的设置，命中金额不得大于" . (($money - $num + 1) / 100) . "元";
    }

    if (($specifyMoney === -1 and $specifyIndex !== -1) or ($specifyMoney !== -1 and $specifyIndex === -1)) {
        return "命中位置和命中金额必须同时设置";
    }

    echo (new LuckRedPacked($money, $num, $specifyIndex, $specifyMoney))->handle();
}


echo go();
echo go();
echo go();
echo go();
echo go();
