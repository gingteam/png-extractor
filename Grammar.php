<?php

declare(strict_types=1);

namespace GingTeam;

/**
 * @author ging-dev <thanh1101dev@gmail.com>
 * @author ciricode <ciricode@protonmail.com>
 */
final class Grammar
{
    /**
     * @return \Generator<string>
     */
    public static function extract(string $filename): \Generator
    {
        $data = fopen($filename, 'rb');
        $num_bytes = fstat($data)['size'];
        $beforeIDAT = '';

        $png_chunk =
        /**
         * @param resource $data
         *
         * @return array{0:string,1:string,2:int}
         */
        function ($data) {
            $lenword = fread($data, 4);
            $length = unpack('N', $lenword)[1];
            $type = fread($data, 4);
            $chunk_data = $length > 0 ? fread($data, $length) : '';
            $crc = fread($data, 4);
            $chunk = $lenword.$type.$chunk_data.$crc;

            return [$type, $chunk, $length + 12];
        };

        for ($offset = 0; $offset < $num_bytes; ++$offset) {
            fseek($data, $offset);
            $four_bytes = fread($data, 4);

            // work with .spr case
            if ("\x50\x4c\x54\x45" === $four_bytes && '' === $beforeIDAT) {
                fseek($data, $offset - 4);
                $skip_bytes = 0;
                do {
                    [$type, $chunk, $length] = $png_chunk($data);
                    $beforeIDAT .= $chunk;
                    $skip_bytes += $length;
                } while ('tRNS' === $type);
                $offset += $skip_bytes;
            }

            if ("\x49\x48\x44\x52" === $four_bytes) {
                fseek($data, $offset - 4);
                $png_data = "\x89\x50\x4e\x47\x0d\x0a\x1a\x0a";
                $i = $skip_bytes = 0;
                do {
                    [$type, $chunk, $length] = $png_chunk($data);
                    // Missing PLTE and tRNS
                    if (1 === $i && 'IDAT' === $type) {
                        $png_data .= $beforeIDAT;
                    }
                    $png_data .= $chunk;
                    $skip_bytes += $length;
                    ++$i;
                } while ('IEND' !== $type);
                $offset += $skip_bytes;

                yield $png_data;
            }
        }
        fclose($data);
    }
}
