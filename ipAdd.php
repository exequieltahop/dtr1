<?php
    // Define the allowed IP ranges for the Wi-Fi networks
    $allowedIPRanges = [
        'Wi-Fi Network 1' => ['192.168.70.14/24'],
        'Wi-Fi Network 2' => ['192.168.7.33/24']
    ];
    $visitorIP = $_SERVER['REMOTE_ADDR'];
    // Function to check if the IP address belongs to any of the allowed ranges
    function isIPAllowed(string $ip, array $ranges): bool {
        foreach ($ranges as $range) {
            [$subnet, $mask] = explode('/', $range);
            if ((ip2long($ip) & ~((1 << (32 - $mask)) - 1)) == ip2long($subnet)) {
                return true;
            }
        }
        return false;
    }
    // Check if the visitor's IP is allowed
    $isAllowed = false;
    foreach ($allowedIPRanges as $network => $ranges) {
        if (isIPAllowed($visitorIP, $ranges)) {
            $isAllowed = true;
            break;
        }
    }
    // If the IP is allowed, allow access, otherwise display an error message
    if ($isAllowed) {
        echo "Welcome to the website!";
    } else {
        echo "Access to this website is restricted based on your Wi-Fi network.";
    }
?>
