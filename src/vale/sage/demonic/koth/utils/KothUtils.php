<?php namespace vale\sage\demonic\koth\utils;

class KothUtils {

    // this MIGHT be used. if not just remove it, unless keep it here because it might be useful in other parts of the core
    public function betterUnset(array $arr, string $unset) : array {
        $new = [];
        foreach ($arr as $k => $v) {
            if ($k == $unset) continue;
            $new[$k] = $v;
        }
        return $new;
    }

    public function secondsToCD(int $int) : string {
        $m = floor($int / 60);
        $s = floor($int % 60);
        return (($m < 10 ? "0" : "").$m.":".($s < 10 ? "0" : "").$s);
    }


}