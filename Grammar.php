<?php

declare(strict_types=1);

namespace GingTeam;

/**
 * @author ging-dev <thanh1101dev@gmail.com>
 */
final class Grammar {
    /*
     * @see https://stackoverflow.com/questions/42597139/extracting-jpeg-png-gif-images-from-data-dump/42614668
     */
    public const PNG_REGEXP = '/89504e470d0a1a0a.+?49454e44ae426082/';

    /**
     * Trả về mảng chứa dữ liệu của các ảnh (hex)
     */
    public function findAll(string $data): array
    {
        preg_match_all(self::PNG_REGEXP, bin2hex($data), $matches);

        return $matches[0];
    }
}
