<?php
/**
 * Default WYF layout template.
 *
 * Available view variables:
 * @var string $wyf_app_name Application name
 * @var string $wyf_logo Path to application logo
 * @var string|null $wyf_title Page title
 * @var array|null $wyf_user Authenticated user data as an associative array with keys:
 *      - 'id' (int|string): Unique user identifier
 *      - 'uid' (string): User unique ID
 *      - 'username' (string): User login username
 *      - 'email' (string): User email address
 *      - 'firstname' (string): User first name
 *      - 'lastname' (string): User last name
 */

$user = $wyf_user ?? [];
$username = $user['username'] ?? 'Username';

$initials = '';
if (!empty($user['firstname']) || !empty($user['lastname'])) {
    $initials = mb_substr($user['firstname'] ?? '', 0, 1) . mb_substr($user['lastname'] ?? '', 0, 1);
}
if (empty($initials) && !empty($user['username'])) {
    $parts = preg_split('/[\s._-]+/', trim($user['username']));
    if (count($parts) >= 2) {
        $initials = mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1);
    } else {
        $initials = mb_substr($user['username'], 0, 2);
    }
}
$initials = strtoupper($initials !== '' ? $initials : 'U');

$fzuiColors = [
    '#fc4e4e', // red
    '#21add3', // blue
    '#ffb724', // yellow
    '#3bbf7e', // green
    '#c36ad1', // purple
    '#d06f06', // brown
];
$colorIndex = abs(crc32($username)) % count($fzuiColors);
$avatarColor = $fzuiColors[$colorIndex];
$avatarSvg = sprintf(
    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"><circle cx="20" cy="20" r="20" fill="%s"/><text x="50%%" y="50%%" text-anchor="middle" dominant-baseline="central" fill="#ffffff" font-size="16" font-weight="bold" font-family="system-ui, sans-serif">%s</text></svg>',
    $avatarColor,
    htmlspecialchars($initials)
);
$avatarSrc = 'data:image/svg+xml;base64,' . base64_encode($avatarSvg);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            <?= isset($wyf_title) ? "$wyf_title | $wyf_app_name" : $wyf_app_name ?>
        </title>
        <link href="/css/app.css" type="text/css" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <?= $this->partial('wyf_stylesheets', ['stylesheets' => $stylesheets ?? []]) ?>
        <script src="/js/app.js" ></script>
    </head>
    <body>
        <fz-app-frame>
            <div slot="logo"><img src="<?= $wyf_logo ?>" /></div>
            <fz-sidebar slot="sidebar" color="red">
                <?= $this->partial('wyf_navigation', ['navigation' => $wyf_navigation, 'parent' => '/']) ?>
            </fz-sidebar>
            <div slot="search">Search</div>
            <fz-dropdown right no-arrow slot="topnav">
                <fz-avatar slot="button" size="small">
                    <img slot="image" src="<?= $avatarSrc ?>" alt="<?= htmlspecialchars($username) ?>">
                    <span slot="name"><?= htmlspecialchars($username) ?></span>
                </fz-avatar>
                <fz-menu>
                    <fz-menu-item>Profile</fz-menu-item>
                    <fz-menu-item>Account Settings</fz-menu-item>
                    <fz-menu-separator></fz-menu-separator>
                    <fz-menu-item>Logout</fz-menu-item>
                </fz-menu>
            </fz-dropdown>
            <div class="wyf-wrapper"><?= $contents->u() ?></div>
        </fz-app-frame>
    </body>
</html>
