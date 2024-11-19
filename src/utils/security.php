<?php

// Input fields sanitization technique
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags($data));
}
