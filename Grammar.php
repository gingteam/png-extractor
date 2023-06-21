<?php

declare(strict_types=1);

namespace GingTeam;

/**
 * @author ging-dev <thanh1101dev@gmail.com>
 * @author ciricode <ciricode@protonmail.com> - concac
 */
final class Grammar
{
    /**
     * @param string $filename
     *
     * @return \Generator<string>
     */
    public static function extract(string $filename): \Generator
    {
        $data = fopen($filename, 'rb');
        $num_bytes = fstat($data)['size'];
        for ($i = 0; $i < $num_bytes; ++$i) {
            fseek($data, $i);
            $eight_bytes = fread($data, 8);
            if ("\x89\x50\x4e\x47\x0d\x0a\x1a\x0a" === $eight_bytes) { // PNG signature
                // Next four bytes after signature is the IHDR with the length
                $png_size_bytes = fread($data, 4);
                $png_size = unpack('V', $png_size_bytes)[1];

                // Go back to beginning of image file and extract full thing
                fseek($data, $i);

                // Read the size of image plus the signature
                $png_data = fread($data, $png_size + 8);
                yield $png_data;
            }
        }
        fclose($data);
    }
}
