<?php
// 테스트: 미들웨어 로직 확인

// 테스트 케이스 1: /ko 접속, 쿠키가 en
echo "=== Test 1: URL=/ko, Cookie=en ===" . PHP_EOL;
$path = "ko";
$selectedLang = "en";
$pathSegments = explode('/', trim($path, '/'));
$currentUrlLang = $pathSegments[0] ?? null;
echo "currentUrlLang: " . $currentUrlLang . PHP_EOL;
echo "selectedLang: " . $selectedLang . PHP_EOL;
echo "Should redirect to: /en" . PHP_EOL;
echo PHP_EOL;

// 테스트 케이스 2: /ko/portfolio 접속, 쿠키가 en
echo "=== Test 2: URL=/ko/portfolio, Cookie=en ===" . PHP_EOL;
$path = "ko/portfolio";
$selectedLang = "en";
$pathSegments = explode('/', trim($path, '/'));
$currentUrlLang = $pathSegments[0] ?? null;
echo "currentUrlLang: " . $currentUrlLang . PHP_EOL;
echo "selectedLang: " . $selectedLang . PHP_EOL;
if (in_array($currentUrlLang, ['ko', 'en', 'cn']) && $currentUrlLang !== $selectedLang) {
    $pathSegments[0] = $selectedLang;
    $newPath = implode('/', $pathSegments);
    echo "Should redirect to: /" . $newPath . PHP_EOL;
}
echo PHP_EOL;

// 테스트 케이스 3: /en 접속, 쿠키가 en
echo "=== Test 3: URL=/en, Cookie=en ===" . PHP_EOL;
$path = "en";
$selectedLang = "en";
$pathSegments = explode('/', trim($path, '/'));
$currentUrlLang = $pathSegments[0] ?? null;
echo "currentUrlLang: " . $currentUrlLang . PHP_EOL;
echo "selectedLang: " . $selectedLang . PHP_EOL;
echo "Should NOT redirect" . PHP_EOL;
?>
