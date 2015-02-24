<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>PHPki:{$title}</title>
    <link rel="stylesheet" type="text/css" href="{$style_css}">
    <script type="text/javascript" language="javascript">

        function setVisibility(rowName, show) {

            var actualVisibility=document.getElementById(rowName).style.visibility;

            if(show==false) {
                document.getElementById(rowName).style.visibility = "hidden";
                document.getElementById(rowName).style.display = "none";
            } else {
                document.getElementById(rowName).style.visibility = "visible";
                document.getElementById(rowName).style.display = "";
            }
        }

    </script>
</head>
<body>
<div class={$logo_class}>PHPki</div>
<div class={$title_class}>{$title}</div>
{if $menu eq false or $menu eq 'about'}
{elseif $menu eq 'setup'}
<div class="{$menu_class}">
    <a class="{$menu_class}" href="setup.php">Setup</a>
    <a class="{$menu_class}" href="about.php" target="_about">About</a>
</div>
{elseif $menu eq 'public'}
<div class="{$menu_class}">
{if $DEMO}
    <a class="{$menu_class}" href="index.php">Public</a>
    <a class="{$menu_class}" href="ca/">Manage</a>
{else}
    <a class="{$menu_class}" href="index.php">Menu</a>
{/if}
    <a class="{$menu_class}" href="policy.html" target="help">Policy</a>
    <a class="{$menu_class}" href="help.php" target="_help">Help</a>
    <a class="{$menu_class}" href="about.php" target="_about">About</a>
</div>
{elseif $menu eq 'ca'}
<div class="{$menu_class}">
{if $DEMO}
    <a class="{$menu_class}" href="../index.php">Public</a>
    <a class="{$menu_class}" href="../ca/index.php">Manage</a>
{else}
    <a class="{$menu_class}" href="../index.php">Menu</a>
{/if}
    <a class="{$menu_class}" href="policy.html" target="help">Policy</a>
    <a class="{$menu_class}" href="../help.php" target="_help">Help</a>
    <a class="{$menu_class}" href="../about.php" target="_about">About</a>
</div>
{elseif $menu eq 'default'}
<div class="{$menu_class}">
    <a class="{$menu_class}" href="index.php">Menu</a>
    <a class="{$menu_class}" href="policy.html" target="help">Policy</a>
    <a class="{$menu_class}" href="help.php" target="_help">Help</a>
    <a class="{$menu_class}" href="about.php" target="_about">About</a>
</div>
{/if}
<hr style="width:99%; align:left; color:#99caff;" />
