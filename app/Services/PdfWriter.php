<?php

namespace App\Services;

class PdfWriter
{
    public const PAGE_WIDTH = 595.28;
    public const PAGE_HEIGHT = 841.89;

    private const WIDTH_REGULAR = [
        278, 278, 355, 556, 556, 889, 667, 191, 333, 333, 389, 584, 278, 333, 278, 278,
        556, 556, 556, 556, 556, 556, 556, 556, 556, 556, 278, 278, 584, 584, 584, 556,
        1015, 667, 667, 722, 722, 667, 611, 778, 722, 278, 500, 667, 556, 833, 722, 778,
        667, 778, 722, 667, 611, 722, 667, 944, 667, 667, 611, 278, 278, 278, 469, 556,
        333, 556, 556, 500, 556, 556, 278, 556, 556, 222, 222, 500, 222, 833, 556, 556,
        556, 556, 333, 500, 278, 556, 500, 722, 500, 500, 500, 334, 260, 334, 584,
    ];

    private const WIDTH_BOLD = [
        278, 333, 474, 556, 556, 889, 722, 238, 333, 333, 389, 584, 278, 333, 278, 278,
        556, 556, 556, 556, 556, 556, 556, 556, 556, 556, 333, 333, 584, 584, 584, 611,
        975, 722, 722, 722, 722, 667, 611, 778, 722, 278, 556, 722, 611, 833, 722, 778,
        667, 778, 722, 667, 611, 722, 667, 944, 667, 667, 611, 333, 278, 333, 584, 556,
        333, 556, 611, 556, 611, 556, 333, 611, 611, 278, 278, 556, 278, 889, 611, 611,
        611, 611, 389, 556, 333, 611, 556, 778, 556, 556, 500, 389, 280, 389, 584,
    ];

    private array $ops = [];

    public function textWidth(string $value, float $size = 9, bool $bold = false): float
    {
        $value = $this->toWinAnsi($value);
        $table = $bold ? self::WIDTH_BOLD : self::WIDTH_REGULAR;
        $total = 0;
        $length = strlen($value);
        for ($i = 0; $i < $length; ++$i) {
            $code = ord($value[$i]) - 32;
            $total += $table[$code] ?? 556;
        }

        return ($total * $size) / 1000;
    }

    public function text(float $x, float $y, string $value, float $size = 9, bool $bold = false, array $rgb = [0, 0, 0]): void
    {
        if ("" === trim($value)) {
            return;
        }
        $font = $bold ? "/F2" : "/F1";
        $this->ops[] = sprintf(
            "BT %s %.2F Tf %.3F %.3F %.3F rg 1 0 0 1 %.2F %.2F Tm (%s) Tj ET",
            $font,
            $size,
            $rgb[0],
            $rgb[1],
            $rgb[2],
            $x,
            self::PAGE_HEIGHT - $y,
            $this->escape($value)
        );
    }

    public function textRight(float $right, float $y, string $value, float $size = 9, bool $bold = false, array $rgb = [0, 0, 0]): void
    {
        $this->text($right - $this->textWidth($value, $size, $bold), $y, $value, $size, $bold, $rgb);
    }

    public function textCenter(float $center, float $y, string $value, float $size = 9, bool $bold = false, array $rgb = [0, 0, 0]): void
    {
        $this->text($center - ($this->textWidth($value, $size, $bold) / 2), $y, $value, $size, $bold, $rgb);
    }

    public function textBlock(float $x, float $y, float $maxWidth, string $value, float $size = 9, float $leading = 11, bool $bold = false, array $rgb = [0, 0, 0], int $maxLines = 0): float
    {
        foreach ($this->wrap($value, $maxWidth, $size, $bold, $maxLines) as $line) {
            $this->text($x, $y, $line, $size, $bold, $rgb);
            $y += $leading;
        }

        return $y;
    }

    public function fit(string $value, float $maxWidth, float $size = 9, bool $bold = false): string
    {
        if ($this->textWidth($value, $size, $bold) <= $maxWidth) {
            return $value;
        }

        $limit = $maxWidth - $this->textWidth("...", $size, $bold);
        $kept = "";
        foreach (mb_str_split($value, 1, "UTF-8") as $char) {
            if ($this->textWidth($kept . $char, $size, $bold) > $limit) {
                break;
            }
            $kept .= $char;
        }

        return rtrim($kept) . "...";
    }

    public function wrap(string $value, float $maxWidth, float $size = 9, bool $bold = false, int $maxLines = 0): array
    {
        $words = preg_split("/\s+/", trim((string) $value), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $lines = [];
        $line = "";
        foreach ($words as $word) {
            $candidate = "" === $line ? $word : $line . " " . $word;
            if ($this->textWidth($candidate, $size, $bold) > $maxWidth && "" !== $line) {
                $lines[] = $line;
                $line = $word;
                continue;
            }
            $line = $candidate;
        }
        if ("" !== $line) {
            $lines[] = $line;
        }

        foreach ($lines as $index => $line) {
            $lines[$index] = $this->fit($line, $maxWidth, $size, $bold);
        }

        if ($maxLines > 0 && count($lines) > $maxLines) {
            $lines = array_slice($lines, 0, $maxLines);
            $last = count($lines) - 1;
            $lines[$last] = $this->fit($lines[$last] . " ...", $maxWidth, $size, $bold);
        }

        return $lines;
    }

    public function line(float $x1, float $y1, float $x2, float $y2, float $width = 0.5, array $rgb = [0.8, 0.8, 0.8]): void
    {
        $this->ops[] = sprintf(
            "%.2F w %.3F %.3F %.3F RG %.2F %.2F m %.2F %.2F l S",
            $width,
            $rgb[0],
            $rgb[1],
            $rgb[2],
            $x1,
            self::PAGE_HEIGHT - $y1,
            $x2,
            self::PAGE_HEIGHT - $y2
        );
    }

    public function rect(float $x, float $y, float $width, float $height, ?array $fill = null, ?array $stroke = null, float $lineWidth = 0.5): void
    {
        $shape = sprintf("%.2F %.2F %.2F %.2F re", $x, self::PAGE_HEIGHT - $y - $height, $width, $height);
        if (null !== $fill && null !== $stroke) {
            $this->ops[] = sprintf(
                "%.3F %.3F %.3F rg %.3F %.3F %.3F RG %.2F w %s B",
                $fill[0], $fill[1], $fill[2], $stroke[0], $stroke[1], $stroke[2], $lineWidth, $shape
            );

            return;
        }
        if (null !== $fill) {
            $this->ops[] = sprintf("%.3F %.3F %.3F rg %s f", $fill[0], $fill[1], $fill[2], $shape);

            return;
        }
        $stroke = $stroke ?? [0.8, 0.8, 0.8];
        $this->ops[] = sprintf("%.3F %.3F %.3F RG %.2F w %s S", $stroke[0], $stroke[1], $stroke[2], $lineWidth, $shape);
    }

    public function output(string $title = "Invoice"): string
    {
        $content = implode("\n", $this->ops);
        $objects = [
            1 => "<< /Type /Catalog /Pages 2 0 R >>",
            2 => "<< /Type /Pages /Kids [3 0 R] /Count 1 >>",
            3 => sprintf(
                "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 %.2F %.2F] /Resources << /Font << /F1 5 0 R /F2 6 0 R >> >> /Contents 4 0 R >>",
                self::PAGE_WIDTH,
                self::PAGE_HEIGHT
            ),
            4 => "<< /Length " . strlen($content) . " >>\nstream\n" . $content . "\nendstream",
            5 => "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>",
            6 => "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>",
            7 => "<< /Title (" . $this->escape($title) . ") /Producer (Flubbi) >>",
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [];
        foreach ($objects as $number => $body) {
            $offsets[$number] = strlen($pdf);
            $pdf .= $number . " 0 obj\n" . $body . "\nendobj\n";
        }

        $xref = strlen($pdf);
        $count = count($objects) + 1;
        $pdf .= "xref\n0 " . $count . "\n0000000000 65535 f \n";
        foreach ($offsets as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }
        $pdf .= "trailer\n<< /Size " . $count . " /Root 1 0 R /Info 7 0 R >>\nstartxref\n" . $xref . "\n%%EOF";

        return $pdf;
    }

    private function toWinAnsi(string $value): string
    {
        $value = str_replace(["\u{20B9}", "\u{2018}", "\u{2019}", "\u{201C}", "\u{201D}", "\u{2013}", "\u{2014}"], ["Rs.", "'", "'", '"', '"', "-", "-"], $value);
        $converted = @iconv("UTF-8", "Windows-1252//TRANSLIT//IGNORE", $value);
        if (false === $converted) {
            $converted = preg_replace("/[^\x20-\x7E]/", "", $value);
        }

        return preg_replace("/[^\x20-\xFF]/", " ", (string) $converted);
    }

    private function escape(string $value): string
    {
        return str_replace(["\\", "(", ")", "\r", "\n"], ["\\\\", "\\(", "\\)", "", " "], $this->toWinAnsi($value));
    }
}
