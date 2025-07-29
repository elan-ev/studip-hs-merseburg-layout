<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<header id="main-header">
  <img id="header-logo" alt="Stud.IP Logo" src="https://www.hs-merseburg.de/fileadmin/Hochschule/Ueber_die_Hochschule/Corporate_Communication/Corporate_Design/Logo/230308_HoMe_Minilogo_zweizeilig_HoMe-blau_RGB.jpg">
  <div id="header-button-wrapper">
    <? if (is_object($GLOBALS['perm']) && $GLOBALS['perm']->have_perm('user')): ?>
      <form action="<?= URLHelper::getLink('dispatch.php/logout') ?>" method="post">
        <button class="button" tabindex="0" title="Navigiere zu Logout"
          aria-label="Navigiere zu Logout"><?= _("Abmelden") ?>
        </button>
      </form>
    <? endif; ?>
  </div>
</header>