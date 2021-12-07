<?php

declare(strict_types=1);

namespace GingTeam;

use Composer\Pcre\Regex;

/**
 * @author ging-dev <thanh1101dev@gmail.com>
 * @author ciricode <ciricode@protonmail.com>
 */
final class Grammar
{
    /*
     * @see https://stackoverflow.com/questions/42597139/extracting-jpeg-png-gif-images-from-data-dump/42614668
     */
    public const PNG_REGEXP = '/89504e470d0a1a0a.+?49454e44ae426082/';

    /**
     * @return string[]
     */
    public function findAll(string $data): array
    {
        $result = Regex::matchAll(self::PNG_REGEXP, bin2hex($data));
        if ($result->matched) {
            return $result->matches[0];
        }

        return [];
    }
}
