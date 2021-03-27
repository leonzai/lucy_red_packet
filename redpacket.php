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
        for ($i = 0; $i < $this->numberOfRedPacket; $i++) {
            $index = $i + 1;
            echo "第 $index 个";

            echo $this->lucky($i) / 100 . "元";
            echo "<br>";
        }
    }

    public function lucky($i)
    {
        if ($i === $this->specifyIndex) {
            $this->spareMoney -= $this->specifyMoney;
            return $this->specifyMoney; // 如果是指定的人，直接返回指定的金额
        }
        if ($i === $this->numberOfRedPacket - 1) return $this->spareMoney; // 如果是最后一个，全部返回
        $spareAmount = $this->numberOfRedPacket - $i - 1; // 除去当前的人和已经发送的人，还剩几个人 （这边的人，就是红包的数量）


//         需要至少剩下 minMoney ，用来发给 $spareAmount 个人
        if ($i > $this->specifyIndex) {
            $minMoney = $spareAmount;
        } else {
            $minMoney = ($spareAmount - 1) * 1 + $this->specifyMoney;
        }
        /****************** 这个算法发红包很不公平，前面的人都是大红包，不予采用 start **************************/
//        $maxMoney = $this->spareMoney - $minMoney;
//        $lucyMoney = rand(1, $maxMoney); // $this->spareMoney - $minMoney 就是发给当前用户的最大金额
        /****************** 这个算法发红包很不公平，前面的人都是大红包，不予采用 end **************************/


        if ($i > $this->specifyIndex) {
            $lucyMaxMoney = $this->spareMoney;  // 计算剩余多少钱可以发放到当前用户。
            $maxMoney = $lucyMaxMoney / ($spareAmount + 1) * 2; // 算法，用户最多中多少钱
        } else {
            $lucyMaxMoney = $this->spareMoney - $this->specifyMoney;
            $maxMoney = $lucyMaxMoney / $spareAmount * 2; // 算法，用户最多中多少钱
        }

        $maxMoney = floor($maxMoney);
        if ($maxMoney >$this->spareMoney-$minMoney) {
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
