<?php
// ============================================================================
// Flubbi WhatsApp Cycle - secure config endpoint (CORE PHP, no framework).
// Self-contained: the schedule JSON and the date-wise logs live in THIS folder.
// Copy this whole "cycle" folder to any PHP host (client /public or your main server).
// Returns the schedule JSON only when the token AND domain match; else logs & 403.
// ============================================================================

$TOKEN           = "adc775b457d1c1724902790ffb0c6797d3de11fd";                           // must match the client's ACCESS_TOKEN
$ALLOWED_DOMAINS = ["flubbi.in"];                      // domains you granted access to
$SCHEDULE_FILE   = __DIR__ . "/flubbi_whatsapp_cycle.json";            // schedule (same folder)
$LOG_FILE        = __DIR__ . "/cycle_denied_" . date("Y-m-d") . ".log"; // date-wise log (same folder)

header("Content-Type: application/json");

function client_ip() {
    foreach (array("HTTP_CF_CONNECTING_IP", "HTTP_X_FORWARDED_FOR", "REMOTE_ADDR") as $k) {
        if (!empty($_SERVER[$k])) { return trim(explode(",", $_SERVER[$k])[0]); }
    }
    return "unknown";
}
function header_val($name) {
    $key = "HTTP_" . strtoupper(str_replace("-", "_", $name));
    return isset($_SERVER[$key]) ? trim($_SERVER[$key]) : "";
}
function deny($reason, $token, $domain, $logFile) {
    $line = sprintf("[%s] DENIED (%s) ip=%s domain=%s token=%s ua=%s\n",
        date("Y-m-d H:i:s"), $reason, client_ip(),
        $domain !== "" ? $domain : "-",
        $token !== "" ? substr($token, 0, 6) . "..." : "-",
        isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : "-");
    @file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
    http_response_code(403);
    echo json_encode(array("error" => "forbidden"));
    exit;
}

$token  = header_val("X-Access-Token");
$domain = strtolower(header_val("X-Site-Domain"));

if (!hash_equals($TOKEN, $token))                                        { deny("bad-token", $token, $domain, $LOG_FILE); }
if (!in_array($domain, array_map("strtolower", $ALLOWED_DOMAINS), true)) { deny("unauthorized-domain", $token, $domain, $LOG_FILE); }

$body = @file_get_contents($SCHEDULE_FILE);
if ($body === false) { http_response_code(404); echo json_encode(array("error" => "schedule not found")); exit; }
echo $body;
