<?php

namespace App\Services;

class InvoicePdfService
{
    private const INK = [0.035, 0.086, 0.184];
    private const ACCENT = [0.803, 0.714, 0.463];
    private const WHITE = [1, 1, 1];
    private const MUTED = [0.42, 0.46, 0.53];
    private const SOFT = [0.78, 0.82, 0.88];
    private const HAIRLINE = [0.85, 0.87, 0.9];
    private const TINT = [0.965, 0.97, 0.976];

    private const MARGIN = 40.0;
    private const RIGHT = 555.28;

    private const STATE_CODES = [
        "jammu and kashmir" => "01", "himachal pradesh" => "02", "punjab" => "03", "chandigarh" => "04",
        "uttarakhand" => "05", "haryana" => "06", "delhi" => "07", "rajasthan" => "08",
        "uttar pradesh" => "09", "bihar" => "10", "sikkim" => "11", "arunachal pradesh" => "12",
        "nagaland" => "13", "manipur" => "14", "mizoram" => "15", "tripura" => "16",
        "meghalaya" => "17", "assam" => "18", "west bengal" => "19", "jharkhand" => "20",
        "odisha" => "21", "chhattisgarh" => "22", "madhya pradesh" => "23", "gujarat" => "24",
        "dadra and nagar haveli and daman and diu" => "26", "maharashtra" => "27", "karnataka" => "29",
        "goa" => "30", "lakshadweep" => "31", "kerala" => "32", "tamil nadu" => "33",
        "puducherry" => "34", "andaman and nicobar islands" => "35", "telangana" => "36",
        "andhra pradesh" => "37", "ladakh" => "38",
    ];

    private const ONES = [
        "", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten",
        "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen",
    ];

    private const TENS = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];

    public function invoiceNumber(int $id): string
    {
        $start = (int) config("web.webapp.invoice.start_number", 0);
        $digits = (int) config("web.webapp.invoice.digit_limit", 6);

        return "INV-" . sprintf("%0" . max(1, $digits) . "d", $start + $id);
    }

    public function fileName(object $row): string
    {
        return $this->invoiceNumber((int) $row->p_id) . ".pdf";
    }

    public function taxBreakup(object $row): array
    {
        $base = (float) $row->base_amount;
        $rate = (float) $row->gst_percentage;
        $gst = (float) $row->gst_amount;
        $state = trim((string) ($row->state_name ?? ""));
        $code = self::STATE_CODES[strtolower($state)] ?? "";
        $intra = "" === $code || "24" === $code;

        return [
            "base" => $base,
            "rate" => $rate,
            "gst" => $gst,
            "total" => (float) $row->total_amount,
            "intra_state" => $intra,
            "half_rate" => $rate / 2,
            "half_gst" => round($gst / 2, 2),
            "state_name" => "" === $state ? "Gujarat" : $state,
            "state_code" => "" === $code ? "24" : $code,
        ];
    }

    public function build(object $row): string
    {
        $pdf = new PdfWriter();
        $tax = $this->taxBreakup($row);
        $service = "consultant" === $row->login_type ? "Hire Agent" : "Self Login";
        $sac = (string) config("web.webapp.invoice.sac_code", "997159");

        $this->header($pdf);
        $y = $this->metaStrip($pdf, $row);
        $y = $this->partyBoxes($pdf, $row, $tax, $service, $y);
        $y = $this->itemsTable($pdf, $row, $tax, $service, $sac, $y);
        $y = $this->totals($pdf, $tax, $y);
        $y = $this->amountInWords($pdf, $tax, $y);
        $this->footer($pdf, $y);

        return $pdf->output($this->invoiceNumber((int) $row->p_id));
    }

    private function fitText(PdfWriter $pdf, float $x, float $y, string $value, float $maxWidth, float $size, bool $bold, array $rgb, float $minSize = 6.0): void
    {
        $scaled = $size;
        while ($scaled > $minSize && $pdf->textWidth($value, $scaled, $bold) > $maxWidth) {
            $scaled -= 0.25;
        }
        $pdf->text($x, $y, $pdf->fit($value, $maxWidth, $scaled, $bold), $scaled, $bold, $rgb);
    }

    private function fitTextRight(PdfWriter $pdf, float $right, float $y, string $value, float $maxWidth, float $size, bool $bold, array $rgb, float $minSize = 6.0): void
    {
        $scaled = $size;
        while ($scaled > $minSize && $pdf->textWidth($value, $scaled, $bold) > $maxWidth) {
            $scaled -= 0.25;
        }
        $pdf->textRight($right, $y, $pdf->fit($value, $maxWidth, $scaled, $bold), $scaled, $bold, $rgb);
    }

    private function header(PdfWriter $pdf): void
    {
        $pdf->rect(self::MARGIN, 40, self::RIGHT - self::MARGIN, 66, self::INK);
        $pdf->rect(self::MARGIN, 103, self::RIGHT - self::MARGIN, 3, self::ACCENT);

        $this->fitText($pdf, 52, 62, (string) config("web.store_data.company_name"), 320, 15, true, self::WHITE, 10);
        $y = $pdf->textBlock(52, 77, 270, (string) config("web.store_data.location"), 7.5, 9.5, false, self::SOFT, 2);
        $this->fitText($pdf, 52, $y, config("web.store_data.phone") . "   " . config("web.store_data.support_mail"), 270, 7.5, false, self::SOFT);

        $pdf->textRight(543, 62, "TAX INVOICE", 15, true, self::ACCENT);
        $pdf->textRight(543, 79, "GSTIN  " . config("web.store_data.GSTIN"), 8, true, self::WHITE);
        $pdf->textRight(543, 91, "LLPIN  " . config("web.store_data.LLPIN"), 8, false, self::SOFT);
    }

    private function metaStrip(PdfWriter $pdf, object $row): float
    {
        $top = 124.0;
        $height = 46.0;
        $width = self::RIGHT - self::MARGIN;
        $pdf->rect(self::MARGIN, $top, $width, $height, self::TINT, self::HAIRLINE);

        $cells = [
            ["INVOICE NO", $this->invoiceNumber((int) $row->p_id)],
            ["INVOICE DATE", date("d M Y", strtotime((string) $row->created_at))],
            ["PAYMENT ID", (string) ($row->gateway_payment_id ?: "-")],
            ["ORDER ID", (string) ($row->gateway_transaction_id ?: "-")],
        ];

        $cellWidth = $width / count($cells);
        foreach ($cells as $index => [$label, $value]) {
            $x = self::MARGIN + ($index * $cellWidth) + 12;
            if ($index > 0) {
                $pdf->line(self::MARGIN + ($index * $cellWidth), $top + 8, self::MARGIN + ($index * $cellWidth), $top + $height - 8, 0.5, self::HAIRLINE);
            }
            $pdf->text($x, $top + 17, $label, 6.5, true, self::MUTED);
            $this->fitText($pdf, $x, $top + 32, $value, $cellWidth - 22, 8.5, true, self::INK);
        }

        return $top + $height;
    }

    private function partyBoxes(PdfWriter $pdf, object $row, array $tax, string $service, float $y): float
    {
        $top = $y + 14;
        $gap = 12.0;
        $boxWidth = (self::RIGHT - self::MARGIN - $gap) / 2;
        $inner = $boxWidth - 24;
        $leftX = self::MARGIN;
        $rightX = self::MARGIN + $boxWidth + $gap;

        $address = array_filter([
            trim((string) ($row->u_address ?? "")),
            trim((string) ($row->u_city ?? "")),
            trim((string) ($row->u_pincode ?? "")),
        ]);

        $left = [];
        foreach ($pdf->wrap(ucwords((string) ($row->u_name ?: "-")), $inner, 10, true, 2) as $part) {
            $left[] = [$part, 10, true, 14];
        }
        $left[] = ["Mobile  " . ($row->u_phone ?: "-"), 8, false, 12];
        $left[] = ["Email  " . ($row->u_email ?: "-"), 8, false, 12];
        foreach ($pdf->wrap(implode(", ", $address), $inner, 8, false, 3) as $part) {
            $left[] = [$part, 8, false, 11];
        }
        $left[] = ["Place of Supply  " . $tax["state_code"] . " - " . $tax["state_name"], 8, false, 12];

        $right = [
            [(string) ($row->app_uuid ?: "-"), 10, true, 14],
            ["Loan Type  " . ($row->loan_type_label ?: "-"), 8, false, 12],
            ["Service  " . $service, 8, false, 12],
            ["Paid Via  " . ucfirst((string) ($row->payment_gateway ?: "-")), 8, false, 12],
            ["Paid On  " . date("d M Y, h:i A", strtotime((string) $row->created_at)), 8, false, 12],
        ];

        $stack = static function (array $rows): float {
            $total = 0.0;
            foreach ($rows as $item) {
                $total += $item[3];
            }

            return $total;
        };

        $height = 36 + max($stack($left), $stack($right)) + 4;

        $pdf->rect($leftX, $top, $boxWidth, $height, null, self::HAIRLINE);
        $pdf->rect($rightX, $top, $boxWidth, $height, null, self::HAIRLINE);
        $pdf->rect($leftX, $top, $boxWidth, 20, self::TINT);
        $pdf->rect($rightX, $top, $boxWidth, 20, self::TINT);
        $pdf->line($leftX, $top + 20, $leftX + $boxWidth, $top + 20, 0.5, self::HAIRLINE);
        $pdf->line($rightX, $top + 20, $rightX + $boxWidth, $top + 20, 0.5, self::HAIRLINE);

        $pdf->text($leftX + 12, $top + 14, "BILLED TO", 7, true, self::MUTED);
        $pdf->text($rightX + 12, $top + 14, "APPLICATION", 7, true, self::MUTED);

        foreach ([[$leftX, $left], [$rightX, $right]] as [$x, $rows]) {
            $line = $top + 36;
            foreach ($rows as [$value, $size, $bold, $leading]) {
                $this->fitText($pdf, $x + 12, $line, $value, $inner, $size, $bold, self::INK);
                $line += $leading;
            }
        }

        return $top + $height;
    }

    private function itemsTable(PdfWriter $pdf, object $row, array $tax, string $service, string $sac, float $y): float
    {
        $top = $y + 20;
        $width = self::RIGHT - self::MARGIN;
        $pdf->rect(self::MARGIN, $top, $width, 22, self::INK);
        $pdf->text(self::MARGIN + 10, $top + 14, "#", 7.5, true, self::WHITE);
        $pdf->text(self::MARGIN + 30, $top + 14, "DESCRIPTION", 7.5, true, self::WHITE);
        $pdf->text(390, $top + 14, "SAC", 7.5, true, self::WHITE);
        $pdf->textRight(545, $top + 14, "TAXABLE VALUE", 7.5, true, self::WHITE);

        $rowTop = $top + 22;
        $description = "Loan Consultancy Platform Fee - " . $service;
        $note = "Facilitation and processing charge for " . ($row->loan_type_label ?: "loan") . " application " . ($row->app_uuid ?: "");

        $pdf->text(self::MARGIN + 10, $rowTop + 18, "1", 9, false, self::INK);
        $this->fitText($pdf, self::MARGIN + 30, $rowTop + 18, $description, 310, 9, true, self::INK);
        $pdf->textBlock(self::MARGIN + 30, $rowTop + 31, 310, $note, 7.5, 10, false, self::MUTED, 2);
        $this->fitText($pdf, 390, $rowTop + 18, $sac, 55, 9, false, self::INK);
        $pdf->textRight(545, $rowTop + 18, number_format($tax["base"], 2), 9, false, self::INK);

        $bottom = $rowTop + 52;
        $pdf->line(self::MARGIN, $bottom, self::RIGHT, $bottom, 0.5, self::HAIRLINE);

        return $bottom;
    }

    private function totals(PdfWriter $pdf, array $tax, float $y): float
    {
        $labelRight = 468.0;
        $valueRight = 545.0;
        $line = $y + 20;

        $rows = [["Taxable Value", number_format($tax["base"], 2)]];
        if ($tax["intra_state"]) {
            $rows[] = ["CGST @ " . $this->rate($tax["half_rate"]) . "%", number_format($tax["half_gst"], 2)];
            $rows[] = ["SGST @ " . $this->rate($tax["half_rate"]) . "%", number_format(round($tax["gst"] - $tax["half_gst"], 2), 2)];
        } else {
            $rows[] = ["IGST @ " . $this->rate($tax["rate"]) . "%", number_format($tax["gst"], 2)];
        }

        foreach ($rows as [$label, $value]) {
            $pdf->textRight($labelRight, $line, $label, 8.5, false, self::MUTED);
            $pdf->textRight($valueRight, $line, $value, 8.5, false, self::INK);
            $line += 15;
        }

        $boxTop = $line - 6;
        $pdf->rect(330, $boxTop, self::RIGHT - 330, 26, self::INK);
        $pdf->text(342, $boxTop + 17, "TOTAL PAID (INR)", 9, true, self::WHITE);
        $this->fitTextRight($pdf, $valueRight, $boxTop + 17, "Rs. " . number_format($tax["total"], 2), 100, 11, true, self::ACCENT);

        return $boxTop + 26;
    }

    private function amountInWords(PdfWriter $pdf, array $tax, float $y): float
    {
        $top = $y + 16;
        $width = self::RIGHT - self::MARGIN;
        $pdf->rect(self::MARGIN, $top, $width, 32, self::TINT, self::HAIRLINE);
        $pdf->text(self::MARGIN + 12, $top + 13, "AMOUNT IN WORDS", 6.5, true, self::MUTED);
        $this->fitText($pdf, self::MARGIN + 12, $top + 26, $this->words($tax["total"]), $width - 24, 8.5, true, self::INK);

        return $top + 32;
    }

    private function footer(PdfWriter $pdf, float $y): void
    {
        $top = $y + 20;
        $pdf->text(self::MARGIN, $top, "DECLARATION", 6.5, true, self::MUTED);
        $pdf->textBlock(
            self::MARGIN,
            $top + 13,
            300,
            "Flubbi Fintech LLP is a loan consultancy and technology service provider, not a lender. The amount shown above is a one time platform service fee and is not refundable once the service is delivered.",
            7.5,
            10,
            false,
            self::MUTED
        );

        $this->fitTextRight($pdf, 545, $top + 6, "For " . config("web.store_data.company_name"), 190, 8.5, true, self::INK);
        $pdf->line(400, $top + 48, 545, $top + 48, 0.5, self::HAIRLINE);
        $pdf->textRight(545, $top + 59, "Authorised Signatory", 7.5, false, self::MUTED);

        $pdf->line(self::MARGIN, 800, self::RIGHT, 800, 0.5, self::HAIRLINE);
        $pdf->textCenter((self::MARGIN + self::RIGHT) / 2, 812, "This is a computer generated invoice and does not require a physical signature.", 7, false, self::MUTED);
    }

    private function rate(float $value): string
    {
        return rtrim(rtrim(number_format($value, 2, ".", ""), "0"), ".");
    }

    private function words(float $amount): string
    {
        $rupees = (int) floor($amount);
        $paise = (int) round(($amount - $rupees) * 100);

        $text = "Rupees " . ($rupees > 0 ? $this->indian($rupees) : "Zero");
        if ($paise > 0) {
            $text .= " and " . $this->indian($paise) . " Paise";
        }

        return $text . " Only";
    }

    private function indian(int $number): string
    {
        if ($number <= 0) {
            return "Zero";
        }

        $parts = [];
        $units = [10000000 => "Crore", 100000 => "Lakh", 1000 => "Thousand", 100 => "Hundred"];
        foreach ($units as $value => $label) {
            if ($number >= $value) {
                $parts[] = $this->indian(intdiv($number, $value)) . " " . $label;
                $number %= $value;
            }
        }

        if ($number > 0) {
            $parts[] = $number < 20
                ? self::ONES[$number]
                : trim(self::TENS[intdiv($number, 10)] . " " . self::ONES[$number % 10]);
        }

        return trim(implode(" ", $parts));
    }
}
